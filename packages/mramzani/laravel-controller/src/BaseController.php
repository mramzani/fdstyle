<?php

namespace Mramzani\LaravelController;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Modules\ExpenseIncome\Providers\ExpenseIncomeServiceProvider;
use Mramzani\LaravelController\Exception\BaseException;
use Mramzani\LaravelController\Exception\ResourceNotFoundException;
use Mramzani\LaravelController\Exception\ViewNotFoundException;
use Mramzani\LaravelController\ExtendedRelations\BelongsToMany;
use Nwidart\Modules\Facades\Module;
use Throwable;
use function GuzzleHttp\Promise\all;

class BaseController extends Controller
{
    protected $model = null;

    protected $module = null;

    private $table = null;

    protected $indexRequest = null;

    protected $storeRequest = null;

    protected $showRequest = null;

    protected $updateRequest = null;

    protected $deleteRequest = null;

    private $query = null;

    protected $viewPath = null;

    protected ?int $paginate_number = null;

    private $primaryKey;

    public function __construct()
    {
        if ($this->model) {
            // Only if model is defined. Otherwise, this is a normal controller
            $this->primaryKey = call_user_func([new $this->model(), "getKeyName"]);
            $this->table = call_user_func([new $this->model(), "getTable"]);
        }

    }


    /**
     * @throws BindingResolutionException
     */
    public function index()
    {
        
        try {
            $this->validate();

            $results = $this->extractQuery()
                ->modify()
                ->getResults();

            

            $view = $this->view();
           

            return BaseView::make(null, $results, $view);
        } catch (BaseException $exception) {
            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertDanger');
        }

    }


    /**
     * @throws BindingResolutionException
     */
    public function show(...$args)
    {
        try {
            $id = last(func_get_args());

            $this->validate();

            $results = $this->extractQuery()
                ->addKeyConstraint($id)
                ->modify()
                ->getResults(true)
                ->first();

            $view = $this->view();

            return BaseView::make(null, $results, $view);
        } catch (BaseException $exception) {
            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertDanger');
        }

    }


    /**
     */
    public function create()
    {
        try {
            $view = $this->view();
            return BaseView::make(null, null, $view);
        } catch (BaseException $exception) {
            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertDanger');
        }

    }

    /**
     * @throws BaseException
     * @throws Throwable
     */
    public function store()
    {
        DB::beginTransaction();
        try {
            $this->validate();

            $collect = $this->makeHashPassword();

            $collect = $this->modifyRequest($collect);

            $object = call_user_func(
                [$this->model, "create"],
                $collect->toArray()
            );

            // Run storing if exists
            if (method_exists($this, 'storing')) {
                call_user_func([$this, 'storing'], $object);
            }

            $route = $this->view(false);

            DB::commit();

            return BaseRoute::make(trans('base::messages.object create successfully'), $route);

        } catch (BaseException $exception) {

            DB::rollBack();

            Log::warning($exception->getMessage());

            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertWarning');

        } catch (BindingResolutionException $e) {
            return $e->getMessage();
        }

    }


    public function edit(...$arg)
    {
        try {
            $id = last(func_get_args());

            $results = $this->extractQuery()
                ->addKeyConstraint($id)
                ->modify()
                ->getResults(true)
                ->first();

            $view = $this->view();
            return BaseView::make(null, $results, $view);

        } catch (BaseException $exception) {
            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertDanger');
        }
    }

    /**
     * @param ...$args
     * @return string
     * @throws BaseException
     * @throws Throwable
     */
    public function update(...$args)
    {
        DB::beginTransaction();
        try {
            $id = last(func_get_args());
            $this->validate();

            $object = $this->extractQuery()
                ->addKeyConstraint($id)
                ->modify()
                ->getResults(true)
                ->first();
            if (!$object) {
                throw BaseException::ObjectNotFountException(trans('base::message.OBJECT_NOT_FOUND_EXCEPTION'));
            }
            $collect = $this->makeHashPassword();

            $collect = $this->modifyRequest($collect);

            //if exists updating
            if (method_exists($this, 'updating')) {
                call_user_func([$this, 'updating'], $object);
            }

            call_user_func(
                [$object, "update"],
                $collect->toArray()
            );


            $route = $this->view(false);
            DB::commit();

            return BaseRoute::make(trans('base::messages.data updated successfully'), $route);

        } catch (BaseException $exception) {
            DB::rollBack();
            Log::warning($exception->getMessage());

            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertDanger');

        } catch (BindingResolutionException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(...$args)
    {
        DB::beginTransaction();
        try {
            $id = last(func_get_args());

            $object = $this->extractQuery()
                ->addKeyConstraint($id)
                ->modify()
                ->getResults(true)
                ->first();

            if (method_exists($this, 'deleting')) {
                call_user_func([$this, 'deleting'], $object);
            }

            $object->delete();

            $route = $this->view(false);

            DB::commit();
            return BaseRoute::make(trans('base::messages.data delete successfully'), $route);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::warning($exception->getMessage());
            return BaseRoute::make($exception->getMessage(), $exception->getCode(), 'alertDanger');
        }
    }


    /**
     * @return void
     * @throws BindingResolutionException
     */
    protected function validate(): void
    {
        if ($this->isIndex()) {
            $requestClass = $this->indexRequest;
        } else if ($this->isShow()) {
            $requestClass = $this->showRequest;
        } else if ($this->isUpdate()) {
            $requestClass = $this->updateRequest;
        } else if ($this->isDelete()) {
            $requestClass = $this->deleteRequest;
        } else if ($this->isStore()) {
            $requestClass = $this->storeRequest;
        } else {
            $requestClass = null;
        }

        if ($requestClass) {
            app()->make($requestClass);
        }
    }

    protected function addKeyConstraint($id): self
    {
        // Add equality constraint
        $this->query->where($this->table . "." . ($this->primaryKey), "=", $id);

        return $this;
    }

    /**
     * @throws BaseException
     */
    public function view($view = true)
    {
        if ($this->isIndex()) {
            $this->viewPath = request()->route()->getName();
            if ($this->module != null) {
                $this->viewPath = $this->getModulePath();
            }
        } elseif ($this->isShow()) {
            $this->viewPath = request()->route()->getName();
            if ($this->module != null) {
                $this->viewPath = $this->getModulePath();
            }
        } elseif ($this->isCreate()) {
            $this->viewPath = request()->route()->getName();
            if ($this->module != null) {
                $this->viewPath = $this->getModulePath();
            }
        } elseif ($this->isStore()) {
            $this->viewPath = str_replace('.store', '.index', request()->route()->getName());
            /*if ($this->module != null) {
                $module = Module::find($this->module);
                $lowerName = $module->getLowerName();
                $path = str_replace('dashboard.','::',request()->route()->getName());
                $this->viewPath = $lowerName . str_replace('.store','.index',$path);
            }*/
        } elseif ($this->isEdit()) {
            $this->viewPath = request()->route()->getName();
            if ($this->module != null) {
                $this->viewPath = $this->getModulePath();
            }
        } elseif ($this->isUpdate()) {
            $this->viewPath = str_replace('.update', '.index', request()->route()->getName());
        } elseif ($this->isDelete()) {
            $this->viewPath = str_replace('.destroy', '.index', request()->route()->getName());
        }


        if ($view && !view()->exists($this->viewPath)) {
            throw BaseException::ViewNotFoundException(
                trans('base::messages.VIEW_NOT_FOUND_EXCEPTION')
                . ' => ' . str_replace('.', '/', $this->viewPath) . '.blade.php');
        } elseif (!$view && !Route::has($this->viewPath)) {
            throw BaseException::ViewNotFoundException(
                trans('base::messages.ROUTE_NOT_FOUND_EXCEPTION')
                . ' => ' . $this->viewPath);
        }

        return $this->viewPath;

    }

    /**
     * @return bool
     */
    protected function isIndex(): bool
    {
        return in_array("index", explode(".", request()->route()->getName()));
    }

    /**
     *
     */
    protected function getQuery()
    {
        return $this->query;
    }

    protected function isCreate(): bool
    {
        return in_array("create", explode(".", request()->route()->getName()));
    }

    protected function isShow(): bool
    {
        return in_array("show", explode(".", request()->route()->getName()));
    }

    protected function isEdit(): bool
    {
        return in_array("edit", explode(".", request()->route()->getName()));
    }

    protected function isUpdate(): bool
    {
        return in_array("update", explode(".", request()->route()->getName()));
    }

    protected function isDelete(): bool
    {
        return in_array("destroy", explode(".", request()->route()->getName()));
    }

    protected function isStore(): bool
    {
        return in_array("store", explode(".", request()->route()->getName()));
    }

    protected function extractQuery(): self
    {
        $this->query = call_user_func($this->model . "::query");

        return $this;
    }

    private function modify(): self
    {
        if ($this->isIndex()) {
            $this->query = $this->modifyIndex($this->query);
        } else if ($this->isShow()) {
            $this->query = $this->modifyShow($this->query);
        } else if ($this->isDelete()) {
            $this->query = $this->modifyDelete($this->query);
        } else if ($this->isUpdate()) {
            $this->query = $this->modifyUpdate($this->query);
        }

        return $this;
    }

    protected function modifyIndex($query)
    {
        return $query;
    }

    protected function modifyShow($query)
    {
        return $query;
    }

    protected function modifyDelete($query)
    {
        return $query;
    }

    private function modifyUpdate($query)
    {
        return $query;
    }

    /**
     * @param bool $single
     * @return Collection|LengthAwarePaginator
     * @throws BaseException
     */
    protected function getResults(bool $single = false): Collection|LengthAwarePaginator
    {
        if (!$single) {
            $results = $this->paginate_number != null ? $this->getQuery()->paginate($this->paginate_number) : $this->getQuery()->get();
        } else {
            $results = $this->getQuery()->skip(0)->take(1)->get();

        }


        /*if ($results->count() == 0) {
            throw BaseException::ObjectNotFountException(trans('base::messages.OBJECT_NOT_FOUND_EXCEPTION'));
        }*/


        return $results;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function makeHashPassword(): \Illuminate\Support\Collection
    {
        $collect = collect(request()->except(config('baseConfig.excludes')));

        if ($this->isStore() and $collect->has('password')) {
            $collect->put('password', Hash::make(request()->input('password')));
        } else if ($this->isUpdate() and $collect->has('password')) {
            if ($collect->get('password') == null) {
                $collect->forget('password');
            } else {
                $collect->put('password', Hash::make(request()->input('password')));
            }
        }

        return $collect;
    }

    private function getModulePath(): string
    {
        $module = Module::find($this->module);
        $lowerName = $module->getLowerName();

        return $lowerName . str_replace('dashboard.', '::', request()->route()->getName());
    }

    public function modifyRequest(\Illuminate\Support\Collection $collect): \Illuminate\Support\Collection
    {
        return $collect;
    }

}
