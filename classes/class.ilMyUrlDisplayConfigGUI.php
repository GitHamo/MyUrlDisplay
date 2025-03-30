<?php

declare(strict_types=1);

use ILIAS\Plugins\MyUrlDisplay\MyUrlDisplayServiceInterface;

/**
 * @ilCtrl_IsCalledBy ilMyUrlDisplayConfigGUI: ilObjComponentSettingsGUI
 */
class ilMyUrlDisplayConfigGUI extends ilPluginConfigGUI
{
    private const TXT_PREFIX = 'cfg_';
    private const TXT_PREFIX_FORM = self::TXT_PREFIX . 'form_';
    private const TXT_PREFIX_FORM_LABEL = 'label_';

    private int $user_id;
    protected ilGlobalTemplateInterface $tpl;
    protected ilCtrl $ctrl;
    private MyUrlDisplayServiceInterface $service;

    public function __construct()
    {
        global $ilUser, $tpl, $ilCtrl;

        $this->user_id = $ilUser->getId();
        $this->tpl = $tpl;
        $this->ctrl = $ilCtrl;
        $this->service = ilMyUrlDisplayServiceProvider::getService();
    }

    /**
     * @param string $cmd
     */
    public function performCommand(string $cmd): void
    {
        switch ($cmd) {
            case 'saveConfigurationForm':
                $this->saveForm();
                break;

            case 'configure':
            default:
                $this->showForm();
                break;
        }
    }

    public function configure(): void
    {
        $this->showForm();
    }

    public function saveForm(): void
    {
        $form = $this->createConfigForm();

        if ($form->checkInput()) {
            try {
                $dto = ilUrlConfigFormMapper::fromForm($form, $this->user_id);
                $this->service->save($dto);
                $this->tpl->setOnScreenMessage(
                    'success',
                    $this->getFormTxt('message_success'),
                    true
                );
            } catch (Exception $e) {
                $this->tpl->setOnScreenMessage('failure', $e->getMessage(), true);
            }
        }

        $this->ctrl->redirect($this, 'configure');

        $form->setValuesByPost();

        $this->showForm($form);
    }

    private function showForm(ilPropertyFormGUI $form = null): void
    {
        if (null === $form) {
            $form = $this->createConfigForm();
            $url_config = $this->service->getByUser($this->user_id);

            if($url_config){
                $form = ilUrlConfigFormMapper::populateForm($form, $url_config);
            }
        }

        $this->tpl->setContent(
            $form->getHTML()
        );
    }

    private function createConfigForm(): ilPropertyFormGUI
    {
        $form = new ilPropertyFormGUI();

        // setup form fields

        $id = new ilHiddenInputGUI(ilUrlConfigFormMapper::FORM_INPUT_KEY_ID);

        $protocol = new ilSelectInputGUI($this->getFormLabelTxt(ilUrlConfigFormMapper::FORM_INPUT_KEY_PROTOCOL), ilUrlConfigFormMapper::FORM_INPUT_KEY_PROTOCOL);
        $protocol->setOptions([
            'https' => 'https',
            'http' => 'http',
        ]);

        $domain = new ilTextInputGUI(
            $this->getFormLabelTxt(ilUrlConfigFormMapper::FORM_INPUT_KEY_DOMAIN),
            ilUrlConfigFormMapper::FORM_INPUT_KEY_DOMAIN
        );

        $port = new ilNumberInputGUI(
            $this->getFormLabelTxt(ilUrlConfigFormMapper::FORM_INPUT_KEY_PORT),
            ilUrlConfigFormMapper::FORM_INPUT_KEY_PORT
        );

        $path = new ilTextInputGUI(
            $this->getFormLabelTxt(ilUrlConfigFormMapper::FORM_INPUT_KEY_PATH),
            ilUrlConfigFormMapper::FORM_INPUT_KEY_PATH
        );

        $color = new ilColorPickerInputGUI(
            $this->getFormLabelTxt(ilUrlConfigFormMapper::FORM_INPUT_KEY_COLOR),
            ilUrlConfigFormMapper::FORM_INPUT_KEY_COLOR
        );

        // add form fields

        $form->addItem($id);
        $form->addItem($protocol);
        $form->addItem($domain);
        $form->addItem($port);
        $form->addItem($path);
        $form->addItem($color);

        // add form data

        $form->setFormAction(
            $this->ctrl->getFormAction($this, 'showForm')
        );
        $form->setShowTopButtons(true);

        // add form submit

        $form->addCommandButton('saveConfigurationForm', $this->getFormLabelTxt('save'));

        return $form;
    }

    private function getFormLabelTxt(string $key): string
    {
        return $this->getFormTxt(self::TXT_PREFIX_FORM_LABEL . $key);
    }

    private function getFormTxt(string $key): string
    {
        return $this->getPluginObject()->txt(self::TXT_PREFIX_FORM . $key);
    }
}
