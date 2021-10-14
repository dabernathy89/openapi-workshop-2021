<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response as PsrResponse;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use League\OpenAPIValidation\Schema\Exception\KeywordMismatch;

class CreateTodo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'completed' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $yamlFile = resource_path('openapi/reference/TodoMVC.yaml');

            $psr17Factory = new Psr17Factory;

            $request = (new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory))
                ->createRequest($this);

            $openApiValidator = (new ValidatorBuilder)
                ->fromYamlFile($yamlFile)
                ->getServerRequestValidator();

            try {
                $openApiValidator->validate($request);
            } catch (\Exception $e) {
                $validator->errors()->add('title', 'There was an error with the format of your request.');
            }
        });
    }
}
