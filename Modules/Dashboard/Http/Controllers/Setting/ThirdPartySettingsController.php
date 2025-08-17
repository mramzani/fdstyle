<?php

namespace Modules\Dashboard\Http\Controllers\Setting;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Helper\Setting\ThirdPartySettings;

class ThirdPartySettingsController extends Controller
{
    public function edit(ThirdPartySettings $thirdPartySettings)
    {
        return view('dashboard::settings.third-party', [
            'e_symbol_code' => $thirdPartySettings->e_symbol_code,
            'samandehi_code' => $thirdPartySettings->samandehi_code,
            'mediaad' => $thirdPartySettings->mediaad,
            'goftino' => $thirdPartySettings->goftino,
            'google_tracking_id' => $thirdPartySettings->google_tracking_id,
            'ippanel_api_key' => $thirdPartySettings->ippanel_api_key,
            'ippanel_pattern_id' => $thirdPartySettings->ippanel_pattern_id,
            'zibal_api_key' => $thirdPartySettings->zibal_api_key,
            'zibal_merchant_id' => $thirdPartySettings->zibal_merchant_id,
            'zibal_wallet_id' => $thirdPartySettings->zibal_wallet_id,
            'telegram_chat_id' => $thirdPartySettings->telegram_chat_id,
            'telegram_bot_token' => $thirdPartySettings->telegram_bot_token,
        ]);
    }

    public function updateEnamad(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'e_symbol_code' => 'nullable|string|max:5000'
        ]);
        //validation
        try {

            $this->saveThirdPartySettings(['e_symbol_code'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }

    }

    public function updateSamandehi(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'samandehi_code' => 'nullable|string|max:5000'
        ]);
        try {

            $this->saveThirdPartySettings(['samandehi_code'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }

    public function updateMediaad(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'mediaad' => 'required|string|max:1000'
        ]);
        try {

            $this->saveThirdPartySettings(['mediaad'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }

    public function updateGoftino(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'goftino' => 'required|string|max:1000'
        ]);
        try {

            $this->saveThirdPartySettings(['goftino'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }

    public function updateGtag(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'google_tracking_id' => 'required|string|max:1000'
        ]);
        try {

            $this->saveThirdPartySettings(['google_tracking_id'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }

    public function updateIpPanel(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'ippanel_api_key' => 'required|string|max:1000',
            'ippanel_pattern_id' => 'required|string|max:1000',
        ]);
        try {

            $this->saveThirdPartySettings(['ippanel_api_key', 'ippanel_pattern_id'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }

    public function updateZibal(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'zibal_api_key' => 'required|string|max:1000',
            'zibal_merchant_id' => 'required|string|max:1000',
            'zibal_wallet_id' => 'required|string|max:1000',
        ]);
        try {
            $this->saveThirdPartySettings(['zibal_api_key', 'zibal_merchant_id', 'zibal_wallet_id'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }

    public function updateTelegram(ThirdPartySettings $thirdPartySettings, Request $request)
    {
        $request->validate([
            'telegram_chat_id' => 'required|string|max:1000',
            'telegram_bot_token' => 'required|string|max:1000',
        ]);
        try {
            $this->saveThirdPartySettings(['telegram_chat_id', 'telegram_bot_token'], $request, $thirdPartySettings);

            return $this->redirectSuccessResponse();

        } catch (Exception $e) {
            return $this->redirectFailResponse($e);
        }
    }


    /**
     * @return RedirectResponse
     */
    private function redirectSuccessResponse(): RedirectResponse
    {
        return back()->with('alertSuccess', 'بروزرسانی با موفقیت انجام شد.');
    }

    /**
     * @param Exception $e
     * @return RedirectResponse
     */
    private function redirectFailResponse(Exception $e): RedirectResponse
    {
        return back()->with('alertWarning', 'خطا در بروزرسانی: ' . $e->getMessage());
    }

    private function saveThirdPartySettings(array $fields, Request $request, ThirdPartySettings $thirdPartySettings): void
    {
        foreach ($fields as $field) {
            $thirdPartySettings->$field = $request->input($field) == null ? "" : $request->input($field);
        }
        $thirdPartySettings->save();
    }


}
