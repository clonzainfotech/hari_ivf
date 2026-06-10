<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            $isAjax = 0;
            if($request->ajax()){
                $isAjax = 1;
            }
            if($exception->getStatusCode() == 404) {
                $logName = '404';
            }else{
                $logName = '500';
            }
            $response = $exception->getMessage() ? $exception->getMessage() : $exception->getFile();
            app('App\Http\Controllers\Base\Admin\AdminController')->storeLog($request,$logName,$response);
            if($isAjax == 1){
                return response()->json(['status' => 'false'],$logName);
            }else{
                return response()->view('errors.'.$logName);
            }
            // return $this->renderHttpException($exception);
        }
        return parent::render($request, $exception);
        // return parent::render($request, $exception);
    }
}
