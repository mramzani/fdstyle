<?php

namespace Modules\User\Entities;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\User\Jobs\SendSms;


/**
 * @property mixed $customer
 * @property mixed $code
 */
class Otp extends Model
{

    protected $fillable = ['customer_id', 'code'];

    protected $table = 'otps';

    #region Properties
    /**
     * @var string $_mobile
     */
    private string $_mobile;

    /**
     * @var Customer $_customer
     */
    private Customer $_customer;

    /**
     * @var Otp $_otp
     */
    private Otp $_otp;

    public static function new(): Otp
    {
        return new self();
    }

    #endregion

    #region Action

    /**
     * request to generate new otp-code for specified phone number
     * @param Customer $customer
     * @return Otp
     */
    public function requestCode(Customer $customer): Otp
    {
        $this->_mobile = $customer->mobile;
        $this->_customer = $customer;

        $this->_processOtpRequest();

        return $this->_otp;

    }

    #endregion

    #region OTP-Generation

    /**
     * Check for code existence or not!
     * If code was not exist, generate the new one!
     */
    private function _processOtpRequest(): void
    {
        $otp = self::whereHas('customer', function ($customer) {
            $customer->where('mobile', $this->_mobile);
        })->first();

        //where('mobile', $this->_mobile)->first();
        $otp
            ? $this->_checkCodeExpiration($otp)
            : $this->_generateCode();
    }

    /**
     * check for code expiration.
     * @param Otp $otp
     */
    private function _checkCodeExpiration(Otp $otp): void
    {
        $expiredAt = $otp->created_at->timestamp + config('user.otp.expiration_period');

        Carbon::now()->timestamp > $expiredAt
            ? $this->_generateCode(true)
            : $this->_otp = $otp;
    }

    /**
     * Generate new otp code for specified phone number!
     * @param bool $withDelete
     * @return void
     */
    private function _generateCode(bool $withDelete = false): void
    {
        if ($withDelete) {
            $this->_truncateExpiredCodes();
        }

        $uniqueCode = $this->_generateRandomUniqueCode();

        $this->_otp = self::create([
            'customer_id' => $this->_customer->id,
            'code' => $uniqueCode
        ]);
    }

    /**
     * Generate some new random and unique code for OTP!
     * @return int
     */
    private function _generateRandomUniqueCode(): int
    {
        $digits = config('user.otp.code_length');
        $min = 10 ** ($digits - 1); // minimum 6 Digits
        $max = (10 ** $digits) - 1; // maximum 6 Digits

        try {
            $code = random_int($min, $max);

            return $this->_checkCodeExist($code)
                ? $this->_generateRandomUniqueCode()
                : $code;

        } catch (Exception $exception) {
            Log::critical('[Model][Otp][_generateRandomUniqueCode] => Failed due generating random number!
                >>>> [MSG]' . $exception->getMessage());
            return $this->_generateRandomUniqueCode();
        }
    }

    /**
     * Check code for existence in database
     * @param int $code
     * @return bool
     */
    private function _checkCodeExist(int $code): bool
    {
        return (bool)self::where('code', $code)->first();
    }

    /**
     * truncate and delete previous and expired otp-codes.
     * @return void
     */
    private function _truncateExpiredCodes(): void
    {
        self::where("customer_id", $this->_customer->id)->delete();
    }

    #endregion

    /**
     * Check code expiration in 60 seconds
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->created_at->diffInSeconds(now()) > config('user.otp.expiration_period');
    }

    /**
     * Check that the code is equal to the user input value
     * @param string $code
     * @return bool
     */
    public function isEqualWith(string $code): bool
    {
        return $this->code == $code;
    }


    /*public function sendCode()
    {
        SendSms::dispatch($this->customer,[
            'code' => $this->code,
            'pattern_type' => 'otp',
        ]);
    }*/


    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


}
