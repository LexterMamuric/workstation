<?php

namespace WorkStationDB\project3;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(1, "mi_workstation", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "workstationlist", -1, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}workstation'), false, false, "", "", false, true);
$sideMenu->addMenuItem(12, "mi_user", $MenuLanguage->MenuPhrase("12", "MenuText"), $MenuRelativePath . "userlist", -1, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}user'), false, false, "", "", false, true);
$sideMenu->addMenuItem(14, "mci_Component_List", $MenuLanguage->MenuPhrase("14", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "", "", false, true);
$sideMenu->addMenuItem(13, "mi_component_type", $MenuLanguage->MenuPhrase("13", "MenuText"), $MenuRelativePath . "componenttypelist", 14, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}component_type'), false, false, "", "", false, true);
$sideMenu->addMenuItem(15, "mi_component_category", $MenuLanguage->MenuPhrase("15", "MenuText"), $MenuRelativePath . "componentcategorylist", 14, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}component_category'), false, false, "", "", false, true);
$sideMenu->addMenuItem(16, "mi_component_make", $MenuLanguage->MenuPhrase("16", "MenuText"), $MenuRelativePath . "componentmakelist", 14, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}component_make'), false, false, "", "", false, true);
$sideMenu->addMenuItem(17, "mi_component_model", $MenuLanguage->MenuPhrase("17", "MenuText"), $MenuRelativePath . "componentmodellist", 14, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}component_model'), false, false, "", "", false, true);
$sideMenu->addMenuItem(18, "mi_component_display_size", $MenuLanguage->MenuPhrase("18", "MenuText"), $MenuRelativePath . "componentdisplaysizelist", 14, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}component_display_size'), false, false, "", "", false, true);
$sideMenu->addMenuItem(19, "mi_component_keyboard_layout", $MenuLanguage->MenuPhrase("19", "MenuText"), $MenuRelativePath . "componentkeyboardlayoutlist", 14, "", IsLoggedIn() || AllowListMenu('{620AECAD-8B27-48FA-89EB-3A65B7F85C7C}component_keyboard_layout'), false, false, "", "", false, true);
echo $sideMenu->toScript();
