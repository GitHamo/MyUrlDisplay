<?php

declare(strict_types=1);

use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;

class ilUrlConfigFormMapper
{
    public const FORM_INPUT_KEY_ID = 'item_id';
    public const FORM_INPUT_KEY_PROTOCOL = 'protocol';
    public const FORM_INPUT_KEY_DOMAIN = 'domain';
    public const FORM_INPUT_KEY_PORT = 'port';
    public const FORM_INPUT_KEY_PATH = 'path';
    public const FORM_INPUT_KEY_COLOR = 'color';
    private const DEFAULT_COLOR = '#ffffff';

    public static function fromForm(ilPropertyFormGUI $form, int $user_id): UrlConfigDto
    {
        $id = $form->getInput(self::FORM_INPUT_KEY_ID) ? (int) $form->getInput(self::FORM_INPUT_KEY_ID) : null;
        $protocol = $form->getInput(self::FORM_INPUT_KEY_PROTOCOL);
        $domain = $form->getInput(self::FORM_INPUT_KEY_DOMAIN);
        $port = $form->getInput(self::FORM_INPUT_KEY_PORT) ? (int) $form->getInput(self::FORM_INPUT_KEY_PORT) : null;
        $path = $form->getInput(self::FORM_INPUT_KEY_PATH) ?? null;
        $color = $form->getInput(self::FORM_INPUT_KEY_COLOR) ?? self::DEFAULT_COLOR;

        return new UrlConfigDto(
            $id,
            $user_id,
            $protocol,
            $domain,
            $port,
            $path,
            $color
        );
    }

    public static function populateForm(ilPropertyFormGUI $form, UrlConfig $urlConfig): ilPropertyFormGUI
    {
        $form->setValuesByArray([
            self::FORM_INPUT_KEY_ID => $urlConfig->getId(),
            self::FORM_INPUT_KEY_PROTOCOL => $urlConfig->getProtocol(),
            self::FORM_INPUT_KEY_DOMAIN => $urlConfig->getDomain(),
            self::FORM_INPUT_KEY_PORT => $urlConfig->getPort(),
            self::FORM_INPUT_KEY_PATH => $urlConfig->getPath(),
            self::FORM_INPUT_KEY_COLOR => $urlConfig->getColor(),
        ]);

        return $form;
    }
}
