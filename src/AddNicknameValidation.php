<?php


namespace DuRoom\Nicknames;

use DuRoom\Settings\SettingsRepositoryInterface;
use Illuminate\Validation\Validator;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddNicknameValidation
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var TranslatorInterface
     */
    protected $translator;


    public function __construct(SettingsRepositoryInterface $settings, TranslatorInterface $translator)
    {
        $this->settings = $settings;
        $this->translator = $translator;
    }

    public function __invoke($duroomValidator, Validator $validator)
    {
        $idSuffix = $duroomValidator->getUser() ? ','.$duroomValidator->getUser()->id : '';
        $rules = $validator->getRules();

        $rules['nickname'] = [
            function ($attribute, $value, $fail) {
                $regex = $this->settings->get('duroom-nicknames.regex');
                if ($regex && !preg_match_all("/$regex/", $value)) {
                    $this->translator->trans('duroom-nicknames.api.invalid_nickname_message');
                }
            },
            'min:' . $this->settings->get('duroom-nicknames.min'),
            'max:' . $this->settings->get('duroom-nicknames.max'),
            'nullable'
        ];

        if ($this->settings->get('duroom-nicknames.unique')) {
            $rules['nickname'][] = 'unique:users,username'.$idSuffix;
            $rules['nickname'][] = 'unique:users,nickname'.$idSuffix;
            $rules['username'][] = 'unique:users,nickname'.$idSuffix;
        }

        $validator->setRules($rules);
    }
}
