<?php

namespace WorkStationDB\project3;

// Page object
$WorkstationView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fworkstationview" id="fworkstationview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { workstation: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fworkstationview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fworkstationview")
        .setPageId("view")

        // Multi-Page
        .setMultiPage(true)
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="workstation">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="accordion ew-accordion" id="accordion_WorkstationView"><!-- multi-page accordion -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(1)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation1"><?= $Page->pageCaption(1) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(1)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation1">
            <div class="card-body"><!-- multi-page .accordion-body -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
    <tr id="r_Workstation_Name"<?= $Page->Workstation_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Workstation_Name"><?= $Page->Workstation_Name->caption() ?></span></td>
        <td data-name="Workstation_Name"<?= $Page->Workstation_Name->cellAttributes() ?>>
<span id="el_workstation_Workstation_Name" data-page="1">
<span<?= $Page->Workstation_Name->viewAttributes() ?>>
<?= $Page->Workstation_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
    <tr id="r_Workstation_Remark"<?= $Page->Workstation_Remark->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Workstation_Remark"><?= $Page->Workstation_Remark->caption() ?></span></td>
        <td data-name="Workstation_Remark"<?= $Page->Workstation_Remark->cellAttributes() ?>>
<span id="el_workstation_Workstation_Remark" data-page="1">
<span<?= $Page->Workstation_Remark->viewAttributes() ?>>
<?= $Page->Workstation_Remark->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(2)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation2"><?= $Page->pageCaption(2) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(2)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation2">
            <div class="card-body"><!-- multi-page .accordion-body -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->User_Email->Visible) { // User_Email ?>
    <tr id="r_User_Email"<?= $Page->User_Email->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_User_Email"><?= $Page->User_Email->caption() ?></span></td>
        <td data-name="User_Email"<?= $Page->User_Email->cellAttributes() ?>>
<span id="el_workstation_User_Email" data-page="2">
<span<?= $Page->User_Email->viewAttributes() ?>>
<?= $Page->User_Email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
    <tr id="r_User_Name"<?= $Page->User_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_User_Name"><?= $Page->User_Name->caption() ?></span></td>
        <td data-name="User_Name"<?= $Page->User_Name->cellAttributes() ?>>
<span id="el_workstation_User_Name" data-page="2">
<span<?= $Page->User_Name->viewAttributes() ?>>
<?= $Page->User_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
    <tr id="r_User_Employee_Number"<?= $Page->User_Employee_Number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_User_Employee_Number"><?= $Page->User_Employee_Number->caption() ?></span></td>
        <td data-name="User_Employee_Number"<?= $Page->User_Employee_Number->cellAttributes() ?>>
<span id="el_workstation_User_Employee_Number" data-page="2">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<?= $Page->User_Employee_Number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
    <tr id="r_User_Phone_Number"<?= $Page->User_Phone_Number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_User_Phone_Number"><?= $Page->User_Phone_Number->caption() ?></span></td>
        <td data-name="User_Phone_Number"<?= $Page->User_Phone_Number->cellAttributes() ?>>
<span id="el_workstation_User_Phone_Number" data-page="2">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<?= $Page->User_Phone_Number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
    <tr id="r_Address_Name"<?= $Page->Address_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Address_Name"><?= $Page->Address_Name->caption() ?></span></td>
        <td data-name="Address_Name"<?= $Page->Address_Name->cellAttributes() ?>>
<span id="el_workstation_Address_Name" data-page="2">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<?= $Page->Address_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
    <tr id="r_Address_Street"<?= $Page->Address_Street->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Address_Street"><?= $Page->Address_Street->caption() ?></span></td>
        <td data-name="Address_Street"<?= $Page->Address_Street->cellAttributes() ?>>
<span id="el_workstation_Address_Street" data-page="2">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<?= $Page->Address_Street->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
    <tr id="r_Address_Zipcode"<?= $Page->Address_Zipcode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Address_Zipcode"><?= $Page->Address_Zipcode->caption() ?></span></td>
        <td data-name="Address_Zipcode"<?= $Page->Address_Zipcode->cellAttributes() ?>>
<span id="el_workstation_Address_Zipcode" data-page="2">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<?= $Page->Address_Zipcode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
    <tr id="r_Address_City"<?= $Page->Address_City->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Address_City"><?= $Page->Address_City->caption() ?></span></td>
        <td data-name="Address_City"<?= $Page->Address_City->cellAttributes() ?>>
<span id="el_workstation_Address_City" data-page="2">
<span<?= $Page->Address_City->viewAttributes() ?>>
<?= $Page->Address_City->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
    <tr id="r_Address_Country"<?= $Page->Address_Country->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Address_Country"><?= $Page->Address_Country->caption() ?></span></td>
        <td data-name="Address_Country"<?= $Page->Address_Country->cellAttributes() ?>>
<span id="el_workstation_Address_Country" data-page="2">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<?= $Page->Address_Country->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(3)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation3"><?= $Page->pageCaption(3) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(3)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation3">
            <div class="card-body"><!-- multi-page .accordion-body -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Component_Type->Visible) { // Component_Type ?>
    <tr id="r_Component_Type"<?= $Page->Component_Type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Type"><?= $Page->Component_Type->caption() ?></span></td>
        <td data-name="Component_Type"<?= $Page->Component_Type->cellAttributes() ?>>
<span id="el_workstation_Component_Type" data-page="3">
<span<?= $Page->Component_Type->viewAttributes() ?>>
<?= $Page->Component_Type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Category->Visible) { // Component_Category ?>
    <tr id="r_Component_Category"<?= $Page->Component_Category->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Category"><?= $Page->Component_Category->caption() ?></span></td>
        <td data-name="Component_Category"<?= $Page->Component_Category->cellAttributes() ?>>
<span id="el_workstation_Component_Category" data-page="3">
<span<?= $Page->Component_Category->viewAttributes() ?>>
<?= $Page->Component_Category->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Make->Visible) { // Component_Make ?>
    <tr id="r_Component_Make"<?= $Page->Component_Make->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Make"><?= $Page->Component_Make->caption() ?></span></td>
        <td data-name="Component_Make"<?= $Page->Component_Make->cellAttributes() ?>>
<span id="el_workstation_Component_Make" data-page="3">
<span<?= $Page->Component_Make->viewAttributes() ?>>
<?= $Page->Component_Make->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Model->Visible) { // Component_Model ?>
    <tr id="r_Component_Model"<?= $Page->Component_Model->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Model"><?= $Page->Component_Model->caption() ?></span></td>
        <td data-name="Component_Model"<?= $Page->Component_Model->cellAttributes() ?>>
<span id="el_workstation_Component_Model" data-page="3">
<span<?= $Page->Component_Model->viewAttributes() ?>>
<?= $Page->Component_Model->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
    <tr id="r_Component_Serial_Number"<?= $Page->Component_Serial_Number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Serial_Number"><?= $Page->Component_Serial_Number->caption() ?></span></td>
        <td data-name="Component_Serial_Number"<?= $Page->Component_Serial_Number->cellAttributes() ?>>
<span id="el_workstation_Component_Serial_Number" data-page="3">
<span<?= $Page->Component_Serial_Number->viewAttributes() ?>>
<?= $Page->Component_Serial_Number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
    <tr id="r_Component_Display_Size"<?= $Page->Component_Display_Size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Display_Size"><?= $Page->Component_Display_Size->caption() ?></span></td>
        <td data-name="Component_Display_Size"<?= $Page->Component_Display_Size->cellAttributes() ?>>
<span id="el_workstation_Component_Display_Size" data-page="3">
<span<?= $Page->Component_Display_Size->viewAttributes() ?>>
<?= $Page->Component_Display_Size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
    <tr id="r_Component_Keyboard_Layout"<?= $Page->Component_Keyboard_Layout->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Keyboard_Layout"><?= $Page->Component_Keyboard_Layout->caption() ?></span></td>
        <td data-name="Component_Keyboard_Layout"<?= $Page->Component_Keyboard_Layout->cellAttributes() ?>>
<span id="el_workstation_Component_Keyboard_Layout" data-page="3">
<span<?= $Page->Component_Keyboard_Layout->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(4)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation4"><?= $Page->pageCaption(4) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(4)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation4">
            <div class="card-body"><!-- multi-page .accordion-body -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
    <tr id="r_Component_Type1"<?= $Page->Component_Type1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Type1"><?= $Page->Component_Type1->caption() ?></span></td>
        <td data-name="Component_Type1"<?= $Page->Component_Type1->cellAttributes() ?>>
<span id="el_workstation_Component_Type1" data-page="4">
<span<?= $Page->Component_Type1->viewAttributes() ?>>
<?= $Page->Component_Type1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
    <tr id="r_Component_Category1"<?= $Page->Component_Category1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Category1"><?= $Page->Component_Category1->caption() ?></span></td>
        <td data-name="Component_Category1"<?= $Page->Component_Category1->cellAttributes() ?>>
<span id="el_workstation_Component_Category1" data-page="4">
<span<?= $Page->Component_Category1->viewAttributes() ?>>
<?= $Page->Component_Category1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
    <tr id="r_Component_Make1"<?= $Page->Component_Make1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Make1"><?= $Page->Component_Make1->caption() ?></span></td>
        <td data-name="Component_Make1"<?= $Page->Component_Make1->cellAttributes() ?>>
<span id="el_workstation_Component_Make1" data-page="4">
<span<?= $Page->Component_Make1->viewAttributes() ?>>
<?= $Page->Component_Make1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
    <tr id="r_Component_Model1"<?= $Page->Component_Model1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Model1"><?= $Page->Component_Model1->caption() ?></span></td>
        <td data-name="Component_Model1"<?= $Page->Component_Model1->cellAttributes() ?>>
<span id="el_workstation_Component_Model1" data-page="4">
<span<?= $Page->Component_Model1->viewAttributes() ?>>
<?= $Page->Component_Model1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
    <tr id="r_Component_Serial_Number1"<?= $Page->Component_Serial_Number1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Serial_Number1"><?= $Page->Component_Serial_Number1->caption() ?></span></td>
        <td data-name="Component_Serial_Number1"<?= $Page->Component_Serial_Number1->cellAttributes() ?>>
<span id="el_workstation_Component_Serial_Number1" data-page="4">
<span<?= $Page->Component_Serial_Number1->viewAttributes() ?>>
<?= $Page->Component_Serial_Number1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
    <tr id="r_Component_Display_Size1"<?= $Page->Component_Display_Size1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Display_Size1"><?= $Page->Component_Display_Size1->caption() ?></span></td>
        <td data-name="Component_Display_Size1"<?= $Page->Component_Display_Size1->cellAttributes() ?>>
<span id="el_workstation_Component_Display_Size1" data-page="4">
<span<?= $Page->Component_Display_Size1->viewAttributes() ?>>
<?= $Page->Component_Display_Size1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
    <tr id="r_Component_Keyboard_Layout1"<?= $Page->Component_Keyboard_Layout1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Keyboard_Layout1"><?= $Page->Component_Keyboard_Layout1->caption() ?></span></td>
        <td data-name="Component_Keyboard_Layout1"<?= $Page->Component_Keyboard_Layout1->cellAttributes() ?>>
<span id="el_workstation_Component_Keyboard_Layout1" data-page="4">
<span<?= $Page->Component_Keyboard_Layout1->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(5)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(5)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation5"><?= $Page->pageCaption(5) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(5)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation5">
            <div class="card-body"><!-- multi-page .accordion-body -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
    <tr id="r_Component_Type2"<?= $Page->Component_Type2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Type2"><?= $Page->Component_Type2->caption() ?></span></td>
        <td data-name="Component_Type2"<?= $Page->Component_Type2->cellAttributes() ?>>
<span id="el_workstation_Component_Type2" data-page="5">
<span<?= $Page->Component_Type2->viewAttributes() ?>>
<?= $Page->Component_Type2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
    <tr id="r_Component_Category2"<?= $Page->Component_Category2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Category2"><?= $Page->Component_Category2->caption() ?></span></td>
        <td data-name="Component_Category2"<?= $Page->Component_Category2->cellAttributes() ?>>
<span id="el_workstation_Component_Category2" data-page="5">
<span<?= $Page->Component_Category2->viewAttributes() ?>>
<?= $Page->Component_Category2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
    <tr id="r_Component_Make2"<?= $Page->Component_Make2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Make2"><?= $Page->Component_Make2->caption() ?></span></td>
        <td data-name="Component_Make2"<?= $Page->Component_Make2->cellAttributes() ?>>
<span id="el_workstation_Component_Make2" data-page="5">
<span<?= $Page->Component_Make2->viewAttributes() ?>>
<?= $Page->Component_Make2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
    <tr id="r_Component_Model2"<?= $Page->Component_Model2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Model2"><?= $Page->Component_Model2->caption() ?></span></td>
        <td data-name="Component_Model2"<?= $Page->Component_Model2->cellAttributes() ?>>
<span id="el_workstation_Component_Model2" data-page="5">
<span<?= $Page->Component_Model2->viewAttributes() ?>>
<?= $Page->Component_Model2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
    <tr id="r_Component_Serial_Number2"<?= $Page->Component_Serial_Number2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Serial_Number2"><?= $Page->Component_Serial_Number2->caption() ?></span></td>
        <td data-name="Component_Serial_Number2"<?= $Page->Component_Serial_Number2->cellAttributes() ?>>
<span id="el_workstation_Component_Serial_Number2" data-page="5">
<span<?= $Page->Component_Serial_Number2->viewAttributes() ?>>
<?= $Page->Component_Serial_Number2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
    <tr id="r_Component_Display_Size2"<?= $Page->Component_Display_Size2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Display_Size2"><?= $Page->Component_Display_Size2->caption() ?></span></td>
        <td data-name="Component_Display_Size2"<?= $Page->Component_Display_Size2->cellAttributes() ?>>
<span id="el_workstation_Component_Display_Size2" data-page="5">
<span<?= $Page->Component_Display_Size2->viewAttributes() ?>>
<?= $Page->Component_Display_Size2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
    <tr id="r_Component_Keyboard_Layout2"<?= $Page->Component_Keyboard_Layout2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_workstation_Component_Keyboard_Layout2"><?= $Page->Component_Keyboard_Layout2->caption() ?></span></td>
        <td data-name="Component_Keyboard_Layout2"<?= $Page->Component_Keyboard_Layout2->cellAttributes() ?>>
<span id="el_workstation_Component_Keyboard_Layout2" data-page="5">
<span<?= $Page->Component_Keyboard_Layout2->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
</div>
</div>
<?php } ?>
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
