<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Стандартное сообщение ошибки, если подходящего сообщения не найдено
     */
    const DEFAULT_MESSAGE_ERROR = 'Error';

    /**
     * Стандартное сообщение успеха, если подходящего сообщения не найдено
     */
    const DEFAULT_MESSAGE_SUCCESS = 'Success';

    /**
     * Постфикс конфига с сообщениями успехов
     */
    const CONF_MSG_KEY_SUCCESS = '';

    /**
     * Постфикс конфига с сообщениями ошибок
     */
    const CONF_MSG_KEY_ERROR = '';

    /**
     * @param class-string
     */
    protected $model;

    /**
     * @param string[]
     */
    protected $errorMessages = [];

    /**
     * @param string[]
     */
    protected $successMessages = [];

    public function __construct()
    {
        $this->registerMsgConfigs();
    }

    public function filter(Request $request): JsonResponse
    {
        if ($this->model) {
            $elements = $this->model::filter($request);

            return $this->success(['data' => $elements]);
        }

        return $this->error();
    }

    public function getById(int $id): JsonResponse
    {
        if ($this->model) {
            $el = $this->model::getById($id);

            if (!$el) {
                return $this->notFound();
            }

            return $this->success(['data' => $el->toArray()]);
        }

        return $this->error();
    }

    public function getErrorMessage(string $key): string
    {
        if (!empty($this->errorMessages[$key])) {
            return strval($this->errorMessages[$key]);
        }

        return static::DEFAULT_MESSAGE_ERROR;
    }

    public function getSuccessMessage(string $key): string
    {
        if (!empty($this->successMessages[$key])) {
            return strval($this->successMessages[$key]);
        }

        return static::DEFAULT_MESSAGE_SUCCESS;
    }

    protected function error(string|array $data = [], int $status = 400): JsonResponse
    {
        if (!is_array($data)) {
            $data = [
                'message' => $data,
            ];
        }

        $data['status'] = 'error';

        return response()->json($data, $status);
    }

    protected function notFound(string $message = 'Запрошенный ресурс не найден'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function success(string|array $data = [], int $status = 200): JsonResponse
    {
        if (!is_array($data)) {
            $data = [
                'message' => $data,
            ];
        }

        $data['status'] = 'success';

        if (!isset($data['data'])) {
            $data['data'] = [];
        }

        return response()->json($data, $status);
    }

    /**
     * Регистрирует конфиги с сообщениями успеха и ошибок
     */
    protected function registerMsgConfigs(): void
    {
        if (static::CONF_MSG_KEY_ERROR) {
            $this->errorMessages = config('errors.' . static::CONF_MSG_KEY_ERROR, []);
        } else {
            $this->errorMessages = [];
        }

        if (static::CONF_MSG_KEY_SUCCESS) {
            $this->successMessages = config('successes.' . static::CONF_MSG_KEY_SUCCESS, []);
        } else {
            $this->successMessages = [];
        }
    }
}
