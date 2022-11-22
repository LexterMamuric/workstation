<?php

namespace WorkStationDB\project3;

// Page object
$UserView = &$Page;
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
<form name="fuserview" id="fuserview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fuserview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fuserview")
        .setPageId("view")
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
<input type="hidden" name="t" value="user">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->User_Email->Visible) { // User_Email ?>
    <tr id="r_User_Email"<?= $Page->User_Email->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_User_Email"><?= $Page->User_Email->caption() ?></span></td>
        <td data-name="User_Email"<?= $Page->User_Email->cellAttributes() ?>>
<span id="el_user_User_Email">
<span<?= $Page->User_Email->viewAttributes() ?>>
<?= $Page->User_Email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
    <tr id="r_User_Name"<?= $Page->User_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_User_Name"><?= $Page->User_Name->caption() ?></span></td>
        <td data-name="User_Name"<?= $Page->User_Name->cellAttributes() ?>>
<span id="el_user_User_Name">
<span<?= $Page->User_Name->viewAttributes() ?>>
<?= $Page->User_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
    <tr id="r_User_Employee_Number"<?= $Page->User_Employee_Number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_User_Employee_Number"><?= $Page->User_Employee_Number->caption() ?></span></td>
        <td data-name="User_Employee_Number"<?= $Page->User_Employee_Number->cellAttributes() ?>>
<span id="el_user_User_Employee_Number">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<?= $Page->User_Employee_Number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
    <tr id="r_User_Phone_Number"<?= $Page->User_Phone_Number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_User_Phone_Number"><?= $Page->User_Phone_Number->caption() ?></span></td>
        <td data-name="User_Phone_Number"<?= $Page->User_Phone_Number->cellAttributes() ?>>
<span id="el_user_User_Phone_Number">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<?= $Page->User_Phone_Number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
    <tr id="r_Address_Name"<?= $Page->Address_Name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_Address_Name"><?= $Page->Address_Name->caption() ?></span></td>
        <td data-name="Address_Name"<?= $Page->Address_Name->cellAttributes() ?>>
<span id="el_user_Address_Name">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<?= $Page->Address_Name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
    <tr id="r_Address_Street"<?= $Page->Address_Street->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_Address_Street"><?= $Page->Address_Street->caption() ?></span></td>
        <td data-name="Address_Street"<?= $Page->Address_Street->cellAttributes() ?>>
<span id="el_user_Address_Street">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<?= $Page->Address_Street->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
    <tr id="r_Address_Zipcode"<?= $Page->Address_Zipcode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_Address_Zipcode"><?= $Page->Address_Zipcode->caption() ?></span></td>
        <td data-name="Address_Zipcode"<?= $Page->Address_Zipcode->cellAttributes() ?>>
<span id="el_user_Address_Zipcode">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<?= $Page->Address_Zipcode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
    <tr id="r_Address_City"<?= $Page->Address_City->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_Address_City"><?= $Page->Address_City->caption() ?></span></td>
        <td data-name="Address_City"<?= $Page->Address_City->cellAttributes() ?>>
<span id="el_user_Address_City">
<span<?= $Page->Address_City->viewAttributes() ?>>
<?= $Page->Address_City->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
    <tr id="r_Address_Country"<?= $Page->Address_Country->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_user_Address_Country"><?= $Page->Address_Country->caption() ?></span></td>
        <td data-name="Address_Country"<?= $Page->Address_Country->cellAttributes() ?>>
<span id="el_user_Address_Country">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<?= $Page->Address_Country->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
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
