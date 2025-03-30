<?php

declare(strict_types=1);

class ilMyUrlDisplayUIHookGUI extends ilUIHookPluginGUI
{
    private const TEXT_BLOCK_TITLE = "cfg_block_title";
    private const PART_RIGHT_COLUMN = "right_column";
    private const COMPONENT_DASHBOARD = "Services/Dashboard";
    private const COMPONENT_PERSONAL_DESKTOP = "Services/PersonalDesktop";

    public function getHTML(string $a_comp, string $a_part, array $a_par = []): array
    {
        if (($a_comp === self::COMPONENT_DASHBOARD || $a_comp === self::COMPONENT_PERSONAL_DESKTOP) && $a_part === self::PART_RIGHT_COLUMN) {
            /** @var $ilUser ilObjUser */
            global $ilUser;
    
            $service = ilMyUrlDisplayServiceProvider::getService();
            $user_id = $ilUser->getId();
            $html = "";
            $url_config = $service->getByUser($user_id);

            if($url_config) {
                $plugin = $this->getPluginObject();
                $tpl = $plugin->getTemplate("default/tpl.url_config_display.html", true, true);

                $tpl->setVariable("BLOCK_TITLE", $plugin->txt(self::TEXT_BLOCK_TITLE));
                $tpl->setVariable("BLOCK_COLOR", $url_config->getColor());
                $tpl->setVariable("URL", $url_config);

                $html = $tpl->get();
            }
            
            return [
                "mode" => ilUIHookPluginGUI::PREPEND,
                "html" => $html,
            ];
        }

        return [
            "mode" => ilUIHookPluginGUI::KEEP,
            "html" => "",
        ];
    }
}
