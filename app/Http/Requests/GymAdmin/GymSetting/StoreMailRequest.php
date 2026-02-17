<?php

    namespace App\Http\Requests\GymAdmin\GymSetting;

    use App\Http\Requests\CoreRequest;

    class StoreMailRequest extends CoreRequest
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
            $rules=[
                'email_status'=>'required|in:enabled,disabled'
            ];

            $newRules = [];

            if ($this->mail_status == 'enabled') {
                $newRules = [
                    'mail_driver' => 'required',
                    'mail_name'   => 'required',
                    'mail_email'  => 'required',
                ];
            }
            $rules = array_merge($rules, $newRules);

            $newRules1 = [];

            if ($this->mail_driver == 'smtp') {
                $newRules1 = [
                    'mail_host'       => 'required',
                    'mail_port'       => 'required|numeric',
                    'mail_username'   => 'required',
                    'mail_password'   => 'required',
                    'mail_encryption' => 'required',
                ];
            }

            return array_merge($rules, $newRules1);
        }
    }
