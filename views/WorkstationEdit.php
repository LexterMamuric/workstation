<?php

namespace WorkStationDB\project3;

// Page object
$WorkstationEdit = &$Page;
?>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fworkstationedit" id="fworkstationedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { workstation: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fworkstationedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fworkstationedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["Workstation_Name", [fields.Workstation_Name.visible && fields.Workstation_Name.required ? ew.Validators.required(fields.Workstation_Name.caption) : null], fields.Workstation_Name.isInvalid],
            ["Workstation_Remark", [fields.Workstation_Remark.visible && fields.Workstation_Remark.required ? ew.Validators.required(fields.Workstation_Remark.caption) : null], fields.Workstation_Remark.isInvalid],
            ["User_Email", [fields.User_Email.visible && fields.User_Email.required ? ew.Validators.required(fields.User_Email.caption) : null], fields.User_Email.isInvalid],
            ["User_Name", [fields.User_Name.visible && fields.User_Name.required ? ew.Validators.required(fields.User_Name.caption) : null], fields.User_Name.isInvalid],
            ["User_Employee_Number", [fields.User_Employee_Number.visible && fields.User_Employee_Number.required ? ew.Validators.required(fields.User_Employee_Number.caption) : null], fields.User_Employee_Number.isInvalid],
            ["User_Phone_Number", [fields.User_Phone_Number.visible && fields.User_Phone_Number.required ? ew.Validators.required(fields.User_Phone_Number.caption) : null], fields.User_Phone_Number.isInvalid],
            ["Address_Name", [fields.Address_Name.visible && fields.Address_Name.required ? ew.Validators.required(fields.Address_Name.caption) : null], fields.Address_Name.isInvalid],
            ["Address_Street", [fields.Address_Street.visible && fields.Address_Street.required ? ew.Validators.required(fields.Address_Street.caption) : null], fields.Address_Street.isInvalid],
            ["Address_Zipcode", [fields.Address_Zipcode.visible && fields.Address_Zipcode.required ? ew.Validators.required(fields.Address_Zipcode.caption) : null], fields.Address_Zipcode.isInvalid],
            ["Address_City", [fields.Address_City.visible && fields.Address_City.required ? ew.Validators.required(fields.Address_City.caption) : null], fields.Address_City.isInvalid],
            ["Address_Country", [fields.Address_Country.visible && fields.Address_Country.required ? ew.Validators.required(fields.Address_Country.caption) : null], fields.Address_Country.isInvalid],
            ["Component_Type", [fields.Component_Type.visible && fields.Component_Type.required ? ew.Validators.required(fields.Component_Type.caption) : null], fields.Component_Type.isInvalid],
            ["Component_Category", [fields.Component_Category.visible && fields.Component_Category.required ? ew.Validators.required(fields.Component_Category.caption) : null], fields.Component_Category.isInvalid],
            ["Component_Make", [fields.Component_Make.visible && fields.Component_Make.required ? ew.Validators.required(fields.Component_Make.caption) : null], fields.Component_Make.isInvalid],
            ["Component_Model", [fields.Component_Model.visible && fields.Component_Model.required ? ew.Validators.required(fields.Component_Model.caption) : null], fields.Component_Model.isInvalid],
            ["Component_Serial_Number", [fields.Component_Serial_Number.visible && fields.Component_Serial_Number.required ? ew.Validators.required(fields.Component_Serial_Number.caption) : null], fields.Component_Serial_Number.isInvalid],
            ["Component_Display_Size", [fields.Component_Display_Size.visible && fields.Component_Display_Size.required ? ew.Validators.required(fields.Component_Display_Size.caption) : null], fields.Component_Display_Size.isInvalid],
            ["Component_Keyboard_Layout", [fields.Component_Keyboard_Layout.visible && fields.Component_Keyboard_Layout.required ? ew.Validators.required(fields.Component_Keyboard_Layout.caption) : null], fields.Component_Keyboard_Layout.isInvalid],
            ["Component_Type1", [fields.Component_Type1.visible && fields.Component_Type1.required ? ew.Validators.required(fields.Component_Type1.caption) : null], fields.Component_Type1.isInvalid],
            ["Component_Category1", [fields.Component_Category1.visible && fields.Component_Category1.required ? ew.Validators.required(fields.Component_Category1.caption) : null], fields.Component_Category1.isInvalid],
            ["Component_Make1", [fields.Component_Make1.visible && fields.Component_Make1.required ? ew.Validators.required(fields.Component_Make1.caption) : null], fields.Component_Make1.isInvalid],
            ["Component_Model1", [fields.Component_Model1.visible && fields.Component_Model1.required ? ew.Validators.required(fields.Component_Model1.caption) : null], fields.Component_Model1.isInvalid],
            ["Component_Serial_Number1", [fields.Component_Serial_Number1.visible && fields.Component_Serial_Number1.required ? ew.Validators.required(fields.Component_Serial_Number1.caption) : null], fields.Component_Serial_Number1.isInvalid],
            ["Component_Display_Size1", [fields.Component_Display_Size1.visible && fields.Component_Display_Size1.required ? ew.Validators.required(fields.Component_Display_Size1.caption) : null], fields.Component_Display_Size1.isInvalid],
            ["Component_Keyboard_Layout1", [fields.Component_Keyboard_Layout1.visible && fields.Component_Keyboard_Layout1.required ? ew.Validators.required(fields.Component_Keyboard_Layout1.caption) : null], fields.Component_Keyboard_Layout1.isInvalid],
            ["Component_Type2", [fields.Component_Type2.visible && fields.Component_Type2.required ? ew.Validators.required(fields.Component_Type2.caption) : null], fields.Component_Type2.isInvalid],
            ["Component_Category2", [fields.Component_Category2.visible && fields.Component_Category2.required ? ew.Validators.required(fields.Component_Category2.caption) : null], fields.Component_Category2.isInvalid],
            ["Component_Make2", [fields.Component_Make2.visible && fields.Component_Make2.required ? ew.Validators.required(fields.Component_Make2.caption) : null], fields.Component_Make2.isInvalid],
            ["Component_Model2", [fields.Component_Model2.visible && fields.Component_Model2.required ? ew.Validators.required(fields.Component_Model2.caption) : null], fields.Component_Model2.isInvalid],
            ["Component_Serial_Number2", [fields.Component_Serial_Number2.visible && fields.Component_Serial_Number2.required ? ew.Validators.required(fields.Component_Serial_Number2.caption) : null], fields.Component_Serial_Number2.isInvalid],
            ["Component_Display_Size2", [fields.Component_Display_Size2.visible && fields.Component_Display_Size2.required ? ew.Validators.required(fields.Component_Display_Size2.caption) : null], fields.Component_Display_Size2.isInvalid],
            ["Component_Keyboard_Layout2", [fields.Component_Keyboard_Layout2.visible && fields.Component_Keyboard_Layout2.required ? ew.Validators.required(fields.Component_Keyboard_Layout2.caption) : null], fields.Component_Keyboard_Layout2.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Multi-Page
        .setMultiPage(true)

        // Dynamic selection lists
        .setLists({
            "Component_Type": <?= $Page->Component_Type->toClientList($Page) ?>,
            "Component_Category": <?= $Page->Component_Category->toClientList($Page) ?>,
            "Component_Make": <?= $Page->Component_Make->toClientList($Page) ?>,
            "Component_Model": <?= $Page->Component_Model->toClientList($Page) ?>,
            "Component_Type1": <?= $Page->Component_Type1->toClientList($Page) ?>,
            "Component_Display_Size1": <?= $Page->Component_Display_Size1->toClientList($Page) ?>,
            "Component_Keyboard_Layout1": <?= $Page->Component_Keyboard_Layout1->toClientList($Page) ?>,
            "Component_Type2": <?= $Page->Component_Type2->toClientList($Page) ?>,
            "Component_Category2": <?= $Page->Component_Category2->toClientList($Page) ?>,
            "Component_Make2": <?= $Page->Component_Make2->toClientList($Page) ?>,
            "Component_Model2": <?= $Page->Component_Model2->toClientList($Page) ?>,
            "Component_Display_Size2": <?= $Page->Component_Display_Size2->toClientList($Page) ?>,
            "Component_Keyboard_Layout2": <?= $Page->Component_Keyboard_Layout2->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="workstation">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="accordion ew-accordion" id="accordion_WorkstationEdit"><!-- multi-page accordion -->
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(1)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation1"><?= $Page->pageCaption(1) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(1)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation1"><!-- multi-page accordion .collapse -->
            <div class="accordion-body"><!-- multi-page .accordion-body -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
    <div id="r_Workstation_Name"<?= $Page->Workstation_Name->rowAttributes() ?>>
        <label id="elh_workstation_Workstation_Name" for="x_Workstation_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Workstation_Name->caption() ?><?= $Page->Workstation_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Workstation_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Workstation_Name">
<input type="<?= $Page->Workstation_Name->getInputTextType() ?>" name="x_Workstation_Name" id="x_Workstation_Name" data-table="workstation" data-field="x_Workstation_Name" value="<?= $Page->Workstation_Name->EditValue ?>" data-page="1" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->Workstation_Name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Workstation_Name->formatPattern()) ?>"<?= $Page->Workstation_Name->editAttributes() ?> aria-describedby="x_Workstation_Name_help">
<?= $Page->Workstation_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Workstation_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Workstation_Name">
<span<?= $Page->Workstation_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Workstation_Name->getDisplayValue($Page->Workstation_Name->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Workstation_Name" data-hidden="1" data-page="1" name="x_Workstation_Name" id="x_Workstation_Name" value="<?= HtmlEncode($Page->Workstation_Name->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
    <div id="r_Workstation_Remark"<?= $Page->Workstation_Remark->rowAttributes() ?>>
        <label id="elh_workstation_Workstation_Remark" for="x_Workstation_Remark" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Workstation_Remark->caption() ?><?= $Page->Workstation_Remark->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Workstation_Remark->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Workstation_Remark">
<input type="<?= $Page->Workstation_Remark->getInputTextType() ?>" name="x_Workstation_Remark" id="x_Workstation_Remark" data-table="workstation" data-field="x_Workstation_Remark" value="<?= $Page->Workstation_Remark->EditValue ?>" data-page="1" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->Workstation_Remark->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Workstation_Remark->formatPattern()) ?>"<?= $Page->Workstation_Remark->editAttributes() ?> aria-describedby="x_Workstation_Remark_help">
<?= $Page->Workstation_Remark->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Workstation_Remark->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Workstation_Remark">
<span<?= $Page->Workstation_Remark->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Workstation_Remark->getDisplayValue($Page->Workstation_Remark->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Workstation_Remark" data-hidden="1" data-page="1" name="x_Workstation_Remark" id="x_Workstation_Remark" value="<?= HtmlEncode($Page->Workstation_Remark->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(2)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation2"><?= $Page->pageCaption(2) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(2)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation2"><!-- multi-page accordion .collapse -->
            <div class="accordion-body"><!-- multi-page .accordion-body -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->User_Email->Visible) { // User_Email ?>
    <div id="r_User_Email"<?= $Page->User_Email->rowAttributes() ?>>
        <label id="elh_workstation_User_Email" for="x_User_Email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Email->caption() ?><?= $Page->User_Email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Email->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_User_Email">
<input type="<?= $Page->User_Email->getInputTextType() ?>" name="x_User_Email" id="x_User_Email" data-table="workstation" data-field="x_User_Email" value="<?= $Page->User_Email->EditValue ?>" data-page="2" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Email->formatPattern()) ?>"<?= $Page->User_Email->editAttributes() ?> aria-describedby="x_User_Email_help">
<?= $Page->User_Email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Email->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_User_Email">
<span<?= $Page->User_Email->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Email->getDisplayValue($Page->User_Email->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_User_Email" data-hidden="1" data-page="2" name="x_User_Email" id="x_User_Email" value="<?= HtmlEncode($Page->User_Email->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
    <div id="r_User_Name"<?= $Page->User_Name->rowAttributes() ?>>
        <label id="elh_workstation_User_Name" for="x_User_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Name->caption() ?><?= $Page->User_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_User_Name">
<input type="<?= $Page->User_Name->getInputTextType() ?>" name="x_User_Name" id="x_User_Name" data-table="workstation" data-field="x_User_Name" value="<?= $Page->User_Name->EditValue ?>" data-page="2" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Name->formatPattern()) ?>"<?= $Page->User_Name->editAttributes() ?> aria-describedby="x_User_Name_help">
<?= $Page->User_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_User_Name">
<span<?= $Page->User_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Name->getDisplayValue($Page->User_Name->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_User_Name" data-hidden="1" data-page="2" name="x_User_Name" id="x_User_Name" value="<?= HtmlEncode($Page->User_Name->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
    <div id="r_User_Employee_Number"<?= $Page->User_Employee_Number->rowAttributes() ?>>
        <label id="elh_workstation_User_Employee_Number" for="x_User_Employee_Number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Employee_Number->caption() ?><?= $Page->User_Employee_Number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Employee_Number->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_User_Employee_Number">
<input type="<?= $Page->User_Employee_Number->getInputTextType() ?>" name="x_User_Employee_Number" id="x_User_Employee_Number" data-table="workstation" data-field="x_User_Employee_Number" value="<?= $Page->User_Employee_Number->EditValue ?>" data-page="2" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Employee_Number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Employee_Number->formatPattern()) ?>"<?= $Page->User_Employee_Number->editAttributes() ?> aria-describedby="x_User_Employee_Number_help">
<?= $Page->User_Employee_Number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Employee_Number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_User_Employee_Number">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Employee_Number->getDisplayValue($Page->User_Employee_Number->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_User_Employee_Number" data-hidden="1" data-page="2" name="x_User_Employee_Number" id="x_User_Employee_Number" value="<?= HtmlEncode($Page->User_Employee_Number->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
    <div id="r_User_Phone_Number"<?= $Page->User_Phone_Number->rowAttributes() ?>>
        <label id="elh_workstation_User_Phone_Number" for="x_User_Phone_Number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Phone_Number->caption() ?><?= $Page->User_Phone_Number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Phone_Number->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_User_Phone_Number">
<input type="<?= $Page->User_Phone_Number->getInputTextType() ?>" name="x_User_Phone_Number" id="x_User_Phone_Number" data-table="workstation" data-field="x_User_Phone_Number" value="<?= $Page->User_Phone_Number->EditValue ?>" data-page="2" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Phone_Number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Phone_Number->formatPattern()) ?>"<?= $Page->User_Phone_Number->editAttributes() ?> aria-describedby="x_User_Phone_Number_help">
<?= $Page->User_Phone_Number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Phone_Number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_User_Phone_Number">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Phone_Number->getDisplayValue($Page->User_Phone_Number->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_User_Phone_Number" data-hidden="1" data-page="2" name="x_User_Phone_Number" id="x_User_Phone_Number" value="<?= HtmlEncode($Page->User_Phone_Number->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
    <div id="r_Address_Name"<?= $Page->Address_Name->rowAttributes() ?>>
        <label id="elh_workstation_Address_Name" for="x_Address_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Name->caption() ?><?= $Page->Address_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Address_Name">
<input type="<?= $Page->Address_Name->getInputTextType() ?>" name="x_Address_Name" id="x_Address_Name" data-table="workstation" data-field="x_Address_Name" value="<?= $Page->Address_Name->EditValue ?>" data-page="2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Name->formatPattern()) ?>"<?= $Page->Address_Name->editAttributes() ?> aria-describedby="x_Address_Name_help">
<?= $Page->Address_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Address_Name">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Name->getDisplayValue($Page->Address_Name->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Address_Name" data-hidden="1" data-page="2" name="x_Address_Name" id="x_Address_Name" value="<?= HtmlEncode($Page->Address_Name->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
    <div id="r_Address_Street"<?= $Page->Address_Street->rowAttributes() ?>>
        <label id="elh_workstation_Address_Street" for="x_Address_Street" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Street->caption() ?><?= $Page->Address_Street->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Street->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Address_Street">
<input type="<?= $Page->Address_Street->getInputTextType() ?>" name="x_Address_Street" id="x_Address_Street" data-table="workstation" data-field="x_Address_Street" value="<?= $Page->Address_Street->EditValue ?>" data-page="2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Street->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Street->formatPattern()) ?>"<?= $Page->Address_Street->editAttributes() ?> aria-describedby="x_Address_Street_help">
<?= $Page->Address_Street->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Street->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Address_Street">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Street->getDisplayValue($Page->Address_Street->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Address_Street" data-hidden="1" data-page="2" name="x_Address_Street" id="x_Address_Street" value="<?= HtmlEncode($Page->Address_Street->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
    <div id="r_Address_Zipcode"<?= $Page->Address_Zipcode->rowAttributes() ?>>
        <label id="elh_workstation_Address_Zipcode" for="x_Address_Zipcode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Zipcode->caption() ?><?= $Page->Address_Zipcode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Zipcode->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Address_Zipcode">
<input type="<?= $Page->Address_Zipcode->getInputTextType() ?>" name="x_Address_Zipcode" id="x_Address_Zipcode" data-table="workstation" data-field="x_Address_Zipcode" value="<?= $Page->Address_Zipcode->EditValue ?>" data-page="2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Zipcode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Zipcode->formatPattern()) ?>"<?= $Page->Address_Zipcode->editAttributes() ?> aria-describedby="x_Address_Zipcode_help">
<?= $Page->Address_Zipcode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Zipcode->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Address_Zipcode">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Zipcode->getDisplayValue($Page->Address_Zipcode->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Address_Zipcode" data-hidden="1" data-page="2" name="x_Address_Zipcode" id="x_Address_Zipcode" value="<?= HtmlEncode($Page->Address_Zipcode->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
    <div id="r_Address_City"<?= $Page->Address_City->rowAttributes() ?>>
        <label id="elh_workstation_Address_City" for="x_Address_City" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_City->caption() ?><?= $Page->Address_City->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_City->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Address_City">
<input type="<?= $Page->Address_City->getInputTextType() ?>" name="x_Address_City" id="x_Address_City" data-table="workstation" data-field="x_Address_City" value="<?= $Page->Address_City->EditValue ?>" data-page="2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_City->formatPattern()) ?>"<?= $Page->Address_City->editAttributes() ?> aria-describedby="x_Address_City_help">
<?= $Page->Address_City->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_City->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Address_City">
<span<?= $Page->Address_City->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_City->getDisplayValue($Page->Address_City->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Address_City" data-hidden="1" data-page="2" name="x_Address_City" id="x_Address_City" value="<?= HtmlEncode($Page->Address_City->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
    <div id="r_Address_Country"<?= $Page->Address_Country->rowAttributes() ?>>
        <label id="elh_workstation_Address_Country" for="x_Address_Country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Country->caption() ?><?= $Page->Address_Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Country->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Address_Country">
<input type="<?= $Page->Address_Country->getInputTextType() ?>" name="x_Address_Country" id="x_Address_Country" data-table="workstation" data-field="x_Address_Country" value="<?= $Page->Address_Country->EditValue ?>" data-page="2" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Country->formatPattern()) ?>"<?= $Page->Address_Country->editAttributes() ?> aria-describedby="x_Address_Country_help">
<?= $Page->Address_Country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Country->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Address_Country">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Country->getDisplayValue($Page->Address_Country->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Address_Country" data-hidden="1" data-page="2" name="x_Address_Country" id="x_Address_Country" value="<?= HtmlEncode($Page->Address_Country->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(3)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation3"><?= $Page->pageCaption(3) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(3)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation3"><!-- multi-page accordion .collapse -->
            <div class="accordion-body"><!-- multi-page .accordion-body -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Component_Type->Visible) { // Component_Type ?>
    <div id="r_Component_Type"<?= $Page->Component_Type->rowAttributes() ?>>
        <label id="elh_workstation_Component_Type" for="x_Component_Type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Type->caption() ?><?= $Page->Component_Type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Type->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Type">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Type"
        name="x_Component_Type"
        class="form-select ew-select<?= $Page->Component_Type->isInvalidClass() ?>"
        <?php if (!$Page->Component_Type->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Type"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Type"
        data-page="3"
        data-value-separator="<?= $Page->Component_Type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Type->getPlaceHolder()) ?>"
        <?= $Page->Component_Type->editAttributes() ?>>
        <?= $Page->Component_Type->selectOptionListHtml("x_Component_Type") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Type" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Type->caption() ?>" data-title="<?= $Page->Component_Type->caption() ?>" data-ew-action="add-option" data-el="x_Component_Type" data-url="<?= GetUrl("componenttypeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Type->getErrorMessage() ?></div>
<?= $Page->Component_Type->Lookup->getParamTag($Page, "p_x_Component_Type") ?>
<?php if (!$Page->Component_Type->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Type", selectId: "fworkstationedit_x_Component_Type" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Type?.lookupOptions.length) {
        options.data = { id: "x_Component_Type", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Type", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Type.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Type">
<span<?= $Page->Component_Type->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Type->getDisplayValue($Page->Component_Type->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Type" data-hidden="1" data-page="3" name="x_Component_Type" id="x_Component_Type" value="<?= HtmlEncode($Page->Component_Type->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Category->Visible) { // Component_Category ?>
    <div id="r_Component_Category"<?= $Page->Component_Category->rowAttributes() ?>>
        <label id="elh_workstation_Component_Category" for="x_Component_Category" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Category->caption() ?><?= $Page->Component_Category->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Category->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Category">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Category"
        name="x_Component_Category"
        class="form-select ew-select<?= $Page->Component_Category->isInvalidClass() ?>"
        <?php if (!$Page->Component_Category->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Category"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Category"
        data-page="3"
        data-value-separator="<?= $Page->Component_Category->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Category->getPlaceHolder()) ?>"
        <?= $Page->Component_Category->editAttributes() ?>>
        <?= $Page->Component_Category->selectOptionListHtml("x_Component_Category") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Category" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Category->caption() ?>" data-title="<?= $Page->Component_Category->caption() ?>" data-ew-action="add-option" data-el="x_Component_Category" data-url="<?= GetUrl("componentcategoryaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Category->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Category->getErrorMessage() ?></div>
<?= $Page->Component_Category->Lookup->getParamTag($Page, "p_x_Component_Category") ?>
<?php if (!$Page->Component_Category->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Category", selectId: "fworkstationedit_x_Component_Category" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Category?.lookupOptions.length) {
        options.data = { id: "x_Component_Category", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Category", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Category.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Category">
<span<?= $Page->Component_Category->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Category->getDisplayValue($Page->Component_Category->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Category" data-hidden="1" data-page="3" name="x_Component_Category" id="x_Component_Category" value="<?= HtmlEncode($Page->Component_Category->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Make->Visible) { // Component_Make ?>
    <div id="r_Component_Make"<?= $Page->Component_Make->rowAttributes() ?>>
        <label id="elh_workstation_Component_Make" for="x_Component_Make" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Make->caption() ?><?= $Page->Component_Make->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Make->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Make">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Make"
        name="x_Component_Make"
        class="form-select ew-select<?= $Page->Component_Make->isInvalidClass() ?>"
        <?php if (!$Page->Component_Make->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Make"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Make"
        data-page="3"
        data-value-separator="<?= $Page->Component_Make->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Make->getPlaceHolder()) ?>"
        <?= $Page->Component_Make->editAttributes() ?>>
        <?= $Page->Component_Make->selectOptionListHtml("x_Component_Make") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Make" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Make->caption() ?>" data-title="<?= $Page->Component_Make->caption() ?>" data-ew-action="add-option" data-el="x_Component_Make" data-url="<?= GetUrl("componentmakeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Make->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Make->getErrorMessage() ?></div>
<?= $Page->Component_Make->Lookup->getParamTag($Page, "p_x_Component_Make") ?>
<?php if (!$Page->Component_Make->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Make", selectId: "fworkstationedit_x_Component_Make" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Make?.lookupOptions.length) {
        options.data = { id: "x_Component_Make", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Make", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Make.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Make">
<span<?= $Page->Component_Make->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Make->getDisplayValue($Page->Component_Make->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Make" data-hidden="1" data-page="3" name="x_Component_Make" id="x_Component_Make" value="<?= HtmlEncode($Page->Component_Make->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Model->Visible) { // Component_Model ?>
    <div id="r_Component_Model"<?= $Page->Component_Model->rowAttributes() ?>>
        <label id="elh_workstation_Component_Model" for="x_Component_Model" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Model->caption() ?><?= $Page->Component_Model->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Model->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Model">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Model"
        name="x_Component_Model"
        class="form-select ew-select<?= $Page->Component_Model->isInvalidClass() ?>"
        <?php if (!$Page->Component_Model->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Model"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Model"
        data-page="3"
        data-value-separator="<?= $Page->Component_Model->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Model->getPlaceHolder()) ?>"
        <?= $Page->Component_Model->editAttributes() ?>>
        <?= $Page->Component_Model->selectOptionListHtml("x_Component_Model") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Model" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Model->caption() ?>" data-title="<?= $Page->Component_Model->caption() ?>" data-ew-action="add-option" data-el="x_Component_Model" data-url="<?= GetUrl("componentmodeladdopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Model->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Model->getErrorMessage() ?></div>
<?= $Page->Component_Model->Lookup->getParamTag($Page, "p_x_Component_Model") ?>
<?php if (!$Page->Component_Model->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Model", selectId: "fworkstationedit_x_Component_Model" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Model?.lookupOptions.length) {
        options.data = { id: "x_Component_Model", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Model", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Model.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Model">
<span<?= $Page->Component_Model->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Model->getDisplayValue($Page->Component_Model->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Model" data-hidden="1" data-page="3" name="x_Component_Model" id="x_Component_Model" value="<?= HtmlEncode($Page->Component_Model->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
    <div id="r_Component_Serial_Number"<?= $Page->Component_Serial_Number->rowAttributes() ?>>
        <label id="elh_workstation_Component_Serial_Number" for="x_Component_Serial_Number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Serial_Number->caption() ?><?= $Page->Component_Serial_Number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Serial_Number->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Serial_Number">
<input type="<?= $Page->Component_Serial_Number->getInputTextType() ?>" name="x_Component_Serial_Number" id="x_Component_Serial_Number" data-table="workstation" data-field="x_Component_Serial_Number" value="<?= $Page->Component_Serial_Number->EditValue ?>" data-page="3" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Component_Serial_Number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Component_Serial_Number->formatPattern()) ?>"<?= $Page->Component_Serial_Number->editAttributes() ?> aria-describedby="x_Component_Serial_Number_help">
<?= $Page->Component_Serial_Number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Serial_Number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Serial_Number">
<span<?= $Page->Component_Serial_Number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Serial_Number->getDisplayValue($Page->Component_Serial_Number->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Serial_Number" data-hidden="1" data-page="3" name="x_Component_Serial_Number" id="x_Component_Serial_Number" value="<?= HtmlEncode($Page->Component_Serial_Number->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
    <div id="r_Component_Display_Size"<?= $Page->Component_Display_Size->rowAttributes() ?>>
        <label id="elh_workstation_Component_Display_Size" for="x_Component_Display_Size" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Display_Size->caption() ?><?= $Page->Component_Display_Size->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Display_Size->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Display_Size">
<input type="<?= $Page->Component_Display_Size->getInputTextType() ?>" name="x_Component_Display_Size" id="x_Component_Display_Size" data-table="workstation" data-field="x_Component_Display_Size" value="<?= $Page->Component_Display_Size->EditValue ?>" data-page="3" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Component_Display_Size->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Component_Display_Size->formatPattern()) ?>"<?= $Page->Component_Display_Size->editAttributes() ?> aria-describedby="x_Component_Display_Size_help">
<?= $Page->Component_Display_Size->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Display_Size->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Display_Size">
<span<?= $Page->Component_Display_Size->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Display_Size->getDisplayValue($Page->Component_Display_Size->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Display_Size" data-hidden="1" data-page="3" name="x_Component_Display_Size" id="x_Component_Display_Size" value="<?= HtmlEncode($Page->Component_Display_Size->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
    <div id="r_Component_Keyboard_Layout"<?= $Page->Component_Keyboard_Layout->rowAttributes() ?>>
        <label id="elh_workstation_Component_Keyboard_Layout" for="x_Component_Keyboard_Layout" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Keyboard_Layout->caption() ?><?= $Page->Component_Keyboard_Layout->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Keyboard_Layout->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Keyboard_Layout">
<input type="<?= $Page->Component_Keyboard_Layout->getInputTextType() ?>" name="x_Component_Keyboard_Layout" id="x_Component_Keyboard_Layout" data-table="workstation" data-field="x_Component_Keyboard_Layout" value="<?= $Page->Component_Keyboard_Layout->EditValue ?>" data-page="3" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Component_Keyboard_Layout->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Component_Keyboard_Layout->formatPattern()) ?>"<?= $Page->Component_Keyboard_Layout->editAttributes() ?> aria-describedby="x_Component_Keyboard_Layout_help">
<?= $Page->Component_Keyboard_Layout->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Keyboard_Layout->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Keyboard_Layout">
<span<?= $Page->Component_Keyboard_Layout->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Keyboard_Layout->getDisplayValue($Page->Component_Keyboard_Layout->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Keyboard_Layout" data-hidden="1" data-page="3" name="x_Component_Keyboard_Layout" id="x_Component_Keyboard_Layout" value="<?= HtmlEncode($Page->Component_Keyboard_Layout->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(4)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation4"><?= $Page->pageCaption(4) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(4)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation4"><!-- multi-page accordion .collapse -->
            <div class="accordion-body"><!-- multi-page .accordion-body -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
    <div id="r_Component_Type1"<?= $Page->Component_Type1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Type1" for="x_Component_Type1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Type1->caption() ?><?= $Page->Component_Type1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Type1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Type1">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Type1"
        name="x_Component_Type1"
        class="form-select ew-select<?= $Page->Component_Type1->isInvalidClass() ?>"
        <?php if (!$Page->Component_Type1->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Type1"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Type1"
        data-page="4"
        data-value-separator="<?= $Page->Component_Type1->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Type1->getPlaceHolder()) ?>"
        <?= $Page->Component_Type1->editAttributes() ?>>
        <?= $Page->Component_Type1->selectOptionListHtml("x_Component_Type1") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Type1" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Type1->caption() ?>" data-title="<?= $Page->Component_Type1->caption() ?>" data-ew-action="add-option" data-el="x_Component_Type1" data-url="<?= GetUrl("componenttypeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Type1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Type1->getErrorMessage() ?></div>
<?= $Page->Component_Type1->Lookup->getParamTag($Page, "p_x_Component_Type1") ?>
<?php if (!$Page->Component_Type1->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Type1", selectId: "fworkstationedit_x_Component_Type1" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Type1?.lookupOptions.length) {
        options.data = { id: "x_Component_Type1", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Type1", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Type1.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Type1">
<span<?= $Page->Component_Type1->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Type1->getDisplayValue($Page->Component_Type1->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Type1" data-hidden="1" data-page="4" name="x_Component_Type1" id="x_Component_Type1" value="<?= HtmlEncode($Page->Component_Type1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
    <div id="r_Component_Category1"<?= $Page->Component_Category1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Category1" for="x_Component_Category1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Category1->caption() ?><?= $Page->Component_Category1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Category1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Category1">
    <select
        id="x_Component_Category1"
        name="x_Component_Category1"
        class="form-select ew-select<?= $Page->Component_Category1->isInvalidClass() ?>"
        <?php if (!$Page->Component_Category1->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Category1"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Category1"
        data-page="4"
        data-value-separator="<?= $Page->Component_Category1->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Category1->getPlaceHolder()) ?>"
        <?= $Page->Component_Category1->editAttributes() ?>>
        <?= $Page->Component_Category1->selectOptionListHtml("x_Component_Category1") ?>
    </select>
    <?= $Page->Component_Category1->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Component_Category1->getErrorMessage() ?></div>
<?php if (!$Page->Component_Category1->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Category1", selectId: "fworkstationedit_x_Component_Category1" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Category1?.lookupOptions.length) {
        options.data = { id: "x_Component_Category1", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Category1", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Category1.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Category1">
<span<?= $Page->Component_Category1->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Category1->getDisplayValue($Page->Component_Category1->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Category1" data-hidden="1" data-page="4" name="x_Component_Category1" id="x_Component_Category1" value="<?= HtmlEncode($Page->Component_Category1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
    <div id="r_Component_Make1"<?= $Page->Component_Make1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Make1" for="x_Component_Make1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Make1->caption() ?><?= $Page->Component_Make1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Make1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Make1">
    <select
        id="x_Component_Make1"
        name="x_Component_Make1"
        class="form-select ew-select<?= $Page->Component_Make1->isInvalidClass() ?>"
        <?php if (!$Page->Component_Make1->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Make1"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Make1"
        data-page="4"
        data-value-separator="<?= $Page->Component_Make1->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Make1->getPlaceHolder()) ?>"
        <?= $Page->Component_Make1->editAttributes() ?>>
        <?= $Page->Component_Make1->selectOptionListHtml("x_Component_Make1") ?>
    </select>
    <?= $Page->Component_Make1->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->Component_Make1->getErrorMessage() ?></div>
<?php if (!$Page->Component_Make1->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Make1", selectId: "fworkstationedit_x_Component_Make1" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Make1?.lookupOptions.length) {
        options.data = { id: "x_Component_Make1", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Make1", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Make1.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Make1">
<span<?= $Page->Component_Make1->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Make1->getDisplayValue($Page->Component_Make1->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Make1" data-hidden="1" data-page="4" name="x_Component_Make1" id="x_Component_Make1" value="<?= HtmlEncode($Page->Component_Make1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
    <div id="r_Component_Model1"<?= $Page->Component_Model1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Model1" for="x_Component_Model1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Model1->caption() ?><?= $Page->Component_Model1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Model1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Model1">
<input type="<?= $Page->Component_Model1->getInputTextType() ?>" name="x_Component_Model1" id="x_Component_Model1" data-table="workstation" data-field="x_Component_Model1" value="<?= $Page->Component_Model1->EditValue ?>" data-page="4" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Component_Model1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Component_Model1->formatPattern()) ?>"<?= $Page->Component_Model1->editAttributes() ?> aria-describedby="x_Component_Model1_help">
<?= $Page->Component_Model1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Model1->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Model1">
<span<?= $Page->Component_Model1->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Model1->getDisplayValue($Page->Component_Model1->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Model1" data-hidden="1" data-page="4" name="x_Component_Model1" id="x_Component_Model1" value="<?= HtmlEncode($Page->Component_Model1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
    <div id="r_Component_Serial_Number1"<?= $Page->Component_Serial_Number1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Serial_Number1" for="x_Component_Serial_Number1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Serial_Number1->caption() ?><?= $Page->Component_Serial_Number1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Serial_Number1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Serial_Number1">
<input type="<?= $Page->Component_Serial_Number1->getInputTextType() ?>" name="x_Component_Serial_Number1" id="x_Component_Serial_Number1" data-table="workstation" data-field="x_Component_Serial_Number1" value="<?= $Page->Component_Serial_Number1->EditValue ?>" data-page="4" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Component_Serial_Number1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Component_Serial_Number1->formatPattern()) ?>"<?= $Page->Component_Serial_Number1->editAttributes() ?> aria-describedby="x_Component_Serial_Number1_help">
<?= $Page->Component_Serial_Number1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Serial_Number1->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Serial_Number1">
<span<?= $Page->Component_Serial_Number1->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Serial_Number1->getDisplayValue($Page->Component_Serial_Number1->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Serial_Number1" data-hidden="1" data-page="4" name="x_Component_Serial_Number1" id="x_Component_Serial_Number1" value="<?= HtmlEncode($Page->Component_Serial_Number1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
    <div id="r_Component_Display_Size1"<?= $Page->Component_Display_Size1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Display_Size1" for="x_Component_Display_Size1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Display_Size1->caption() ?><?= $Page->Component_Display_Size1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Display_Size1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Display_Size1">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Display_Size1"
        name="x_Component_Display_Size1"
        class="form-select ew-select<?= $Page->Component_Display_Size1->isInvalidClass() ?>"
        <?php if (!$Page->Component_Display_Size1->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Display_Size1"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Display_Size1"
        data-page="4"
        data-value-separator="<?= $Page->Component_Display_Size1->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Display_Size1->getPlaceHolder()) ?>"
        <?= $Page->Component_Display_Size1->editAttributes() ?>>
        <?= $Page->Component_Display_Size1->selectOptionListHtml("x_Component_Display_Size1") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Display_Size1" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Display_Size1->caption() ?>" data-title="<?= $Page->Component_Display_Size1->caption() ?>" data-ew-action="add-option" data-el="x_Component_Display_Size1" data-url="<?= GetUrl("componentdisplaysizeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Display_Size1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Display_Size1->getErrorMessage() ?></div>
<?= $Page->Component_Display_Size1->Lookup->getParamTag($Page, "p_x_Component_Display_Size1") ?>
<?php if (!$Page->Component_Display_Size1->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Display_Size1", selectId: "fworkstationedit_x_Component_Display_Size1" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Display_Size1?.lookupOptions.length) {
        options.data = { id: "x_Component_Display_Size1", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Display_Size1", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Display_Size1.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Display_Size1">
<span<?= $Page->Component_Display_Size1->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Display_Size1->getDisplayValue($Page->Component_Display_Size1->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Display_Size1" data-hidden="1" data-page="4" name="x_Component_Display_Size1" id="x_Component_Display_Size1" value="<?= HtmlEncode($Page->Component_Display_Size1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
    <div id="r_Component_Keyboard_Layout1"<?= $Page->Component_Keyboard_Layout1->rowAttributes() ?>>
        <label id="elh_workstation_Component_Keyboard_Layout1" for="x_Component_Keyboard_Layout1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Keyboard_Layout1->caption() ?><?= $Page->Component_Keyboard_Layout1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Keyboard_Layout1->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Keyboard_Layout1">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Keyboard_Layout1"
        name="x_Component_Keyboard_Layout1"
        class="form-select ew-select<?= $Page->Component_Keyboard_Layout1->isInvalidClass() ?>"
        <?php if (!$Page->Component_Keyboard_Layout1->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Keyboard_Layout1"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Keyboard_Layout1"
        data-page="4"
        data-value-separator="<?= $Page->Component_Keyboard_Layout1->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Keyboard_Layout1->getPlaceHolder()) ?>"
        <?= $Page->Component_Keyboard_Layout1->editAttributes() ?>>
        <?= $Page->Component_Keyboard_Layout1->selectOptionListHtml("x_Component_Keyboard_Layout1") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Keyboard_Layout1" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Keyboard_Layout1->caption() ?>" data-title="<?= $Page->Component_Keyboard_Layout1->caption() ?>" data-ew-action="add-option" data-el="x_Component_Keyboard_Layout1" data-url="<?= GetUrl("componentkeyboardlayoutaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Keyboard_Layout1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Keyboard_Layout1->getErrorMessage() ?></div>
<?= $Page->Component_Keyboard_Layout1->Lookup->getParamTag($Page, "p_x_Component_Keyboard_Layout1") ?>
<?php if (!$Page->Component_Keyboard_Layout1->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Keyboard_Layout1", selectId: "fworkstationedit_x_Component_Keyboard_Layout1" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Keyboard_Layout1?.lookupOptions.length) {
        options.data = { id: "x_Component_Keyboard_Layout1", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Keyboard_Layout1", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Keyboard_Layout1.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Keyboard_Layout1">
<span<?= $Page->Component_Keyboard_Layout1->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Keyboard_Layout1->getDisplayValue($Page->Component_Keyboard_Layout1->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Keyboard_Layout1" data-hidden="1" data-page="4" name="x_Component_Keyboard_Layout1" id="x_Component_Keyboard_Layout1" value="<?= HtmlEncode($Page->Component_Keyboard_Layout1->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
    <div class="accordion-item ew-accordion-item"><!-- multi-page .accordion-item -->
        <h4 class="accordion-header">
            <button class="accordion-button<?php if (!$Page->MultiPages->isActive(5)) { ?> collapsed<?php } ?>" type="button" aria-expanded="<?= JsonEncode($Page->MultiPages->isActive(5)) ?>" data-bs-toggle="collapse" data-bs-target="#tab_workstation5"><?= $Page->pageCaption(5) ?></button>
        </h4>
        <div class="accordion-collapse collapse<?php if ($Page->MultiPages->isActive(5)) { ?> show<?php } ?>" data-bs-parent="<?= $Page->MultiPages->Parent ?>" id="tab_workstation5"><!-- multi-page accordion .collapse -->
            <div class="accordion-body"><!-- multi-page .accordion-body -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
    <div id="r_Component_Type2"<?= $Page->Component_Type2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Type2" for="x_Component_Type2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Type2->caption() ?><?= $Page->Component_Type2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Type2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Type2">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Type2"
        name="x_Component_Type2"
        class="form-select ew-select<?= $Page->Component_Type2->isInvalidClass() ?>"
        <?php if (!$Page->Component_Type2->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Type2"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Type2"
        data-page="5"
        data-value-separator="<?= $Page->Component_Type2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Type2->getPlaceHolder()) ?>"
        <?= $Page->Component_Type2->editAttributes() ?>>
        <?= $Page->Component_Type2->selectOptionListHtml("x_Component_Type2") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Type2" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Type2->caption() ?>" data-title="<?= $Page->Component_Type2->caption() ?>" data-ew-action="add-option" data-el="x_Component_Type2" data-url="<?= GetUrl("componenttypeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Type2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Type2->getErrorMessage() ?></div>
<?= $Page->Component_Type2->Lookup->getParamTag($Page, "p_x_Component_Type2") ?>
<?php if (!$Page->Component_Type2->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Type2", selectId: "fworkstationedit_x_Component_Type2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Type2?.lookupOptions.length) {
        options.data = { id: "x_Component_Type2", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Type2", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Type2.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Type2">
<span<?= $Page->Component_Type2->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Type2->getDisplayValue($Page->Component_Type2->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Type2" data-hidden="1" data-page="5" name="x_Component_Type2" id="x_Component_Type2" value="<?= HtmlEncode($Page->Component_Type2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
    <div id="r_Component_Category2"<?= $Page->Component_Category2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Category2" for="x_Component_Category2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Category2->caption() ?><?= $Page->Component_Category2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Category2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Category2">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Category2"
        name="x_Component_Category2"
        class="form-select ew-select<?= $Page->Component_Category2->isInvalidClass() ?>"
        <?php if (!$Page->Component_Category2->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Category2"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Category2"
        data-page="5"
        data-value-separator="<?= $Page->Component_Category2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Category2->getPlaceHolder()) ?>"
        <?= $Page->Component_Category2->editAttributes() ?>>
        <?= $Page->Component_Category2->selectOptionListHtml("x_Component_Category2") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Category2" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Category2->caption() ?>" data-title="<?= $Page->Component_Category2->caption() ?>" data-ew-action="add-option" data-el="x_Component_Category2" data-url="<?= GetUrl("componentcategoryaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Category2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Category2->getErrorMessage() ?></div>
<?= $Page->Component_Category2->Lookup->getParamTag($Page, "p_x_Component_Category2") ?>
<?php if (!$Page->Component_Category2->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Category2", selectId: "fworkstationedit_x_Component_Category2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Category2?.lookupOptions.length) {
        options.data = { id: "x_Component_Category2", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Category2", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Category2.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Category2">
<span<?= $Page->Component_Category2->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Category2->getDisplayValue($Page->Component_Category2->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Category2" data-hidden="1" data-page="5" name="x_Component_Category2" id="x_Component_Category2" value="<?= HtmlEncode($Page->Component_Category2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
    <div id="r_Component_Make2"<?= $Page->Component_Make2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Make2" for="x_Component_Make2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Make2->caption() ?><?= $Page->Component_Make2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Make2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Make2">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Make2"
        name="x_Component_Make2"
        class="form-select ew-select<?= $Page->Component_Make2->isInvalidClass() ?>"
        <?php if (!$Page->Component_Make2->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Make2"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Make2"
        data-page="5"
        data-value-separator="<?= $Page->Component_Make2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Make2->getPlaceHolder()) ?>"
        <?= $Page->Component_Make2->editAttributes() ?>>
        <?= $Page->Component_Make2->selectOptionListHtml("x_Component_Make2") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Make2" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Make2->caption() ?>" data-title="<?= $Page->Component_Make2->caption() ?>" data-ew-action="add-option" data-el="x_Component_Make2" data-url="<?= GetUrl("componentmakeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Make2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Make2->getErrorMessage() ?></div>
<?= $Page->Component_Make2->Lookup->getParamTag($Page, "p_x_Component_Make2") ?>
<?php if (!$Page->Component_Make2->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Make2", selectId: "fworkstationedit_x_Component_Make2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Make2?.lookupOptions.length) {
        options.data = { id: "x_Component_Make2", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Make2", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Make2.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Make2">
<span<?= $Page->Component_Make2->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Make2->getDisplayValue($Page->Component_Make2->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Make2" data-hidden="1" data-page="5" name="x_Component_Make2" id="x_Component_Make2" value="<?= HtmlEncode($Page->Component_Make2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
    <div id="r_Component_Model2"<?= $Page->Component_Model2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Model2" for="x_Component_Model2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Model2->caption() ?><?= $Page->Component_Model2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Model2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Model2">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Model2"
        name="x_Component_Model2"
        class="form-select ew-select<?= $Page->Component_Model2->isInvalidClass() ?>"
        <?php if (!$Page->Component_Model2->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Model2"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Model2"
        data-page="5"
        data-value-separator="<?= $Page->Component_Model2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Model2->getPlaceHolder()) ?>"
        <?= $Page->Component_Model2->editAttributes() ?>>
        <?= $Page->Component_Model2->selectOptionListHtml("x_Component_Model2") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Model2" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Model2->caption() ?>" data-title="<?= $Page->Component_Model2->caption() ?>" data-ew-action="add-option" data-el="x_Component_Model2" data-url="<?= GetUrl("componentmodeladdopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Model2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Model2->getErrorMessage() ?></div>
<?= $Page->Component_Model2->Lookup->getParamTag($Page, "p_x_Component_Model2") ?>
<?php if (!$Page->Component_Model2->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Model2", selectId: "fworkstationedit_x_Component_Model2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Model2?.lookupOptions.length) {
        options.data = { id: "x_Component_Model2", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Model2", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Model2.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Model2">
<span<?= $Page->Component_Model2->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Model2->getDisplayValue($Page->Component_Model2->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Model2" data-hidden="1" data-page="5" name="x_Component_Model2" id="x_Component_Model2" value="<?= HtmlEncode($Page->Component_Model2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
    <div id="r_Component_Serial_Number2"<?= $Page->Component_Serial_Number2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Serial_Number2" for="x_Component_Serial_Number2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Serial_Number2->caption() ?><?= $Page->Component_Serial_Number2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Serial_Number2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Serial_Number2">
<input type="<?= $Page->Component_Serial_Number2->getInputTextType() ?>" name="x_Component_Serial_Number2" id="x_Component_Serial_Number2" data-table="workstation" data-field="x_Component_Serial_Number2" value="<?= $Page->Component_Serial_Number2->EditValue ?>" data-page="5" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Component_Serial_Number2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Component_Serial_Number2->formatPattern()) ?>"<?= $Page->Component_Serial_Number2->editAttributes() ?> aria-describedby="x_Component_Serial_Number2_help">
<?= $Page->Component_Serial_Number2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Serial_Number2->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Serial_Number2">
<span<?= $Page->Component_Serial_Number2->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Component_Serial_Number2->getDisplayValue($Page->Component_Serial_Number2->ViewValue))) ?>"></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Serial_Number2" data-hidden="1" data-page="5" name="x_Component_Serial_Number2" id="x_Component_Serial_Number2" value="<?= HtmlEncode($Page->Component_Serial_Number2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
    <div id="r_Component_Display_Size2"<?= $Page->Component_Display_Size2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Display_Size2" for="x_Component_Display_Size2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Display_Size2->caption() ?><?= $Page->Component_Display_Size2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Display_Size2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Display_Size2">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Display_Size2"
        name="x_Component_Display_Size2"
        class="form-select ew-select<?= $Page->Component_Display_Size2->isInvalidClass() ?>"
        <?php if (!$Page->Component_Display_Size2->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Display_Size2"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Display_Size2"
        data-page="5"
        data-value-separator="<?= $Page->Component_Display_Size2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Display_Size2->getPlaceHolder()) ?>"
        <?= $Page->Component_Display_Size2->editAttributes() ?>>
        <?= $Page->Component_Display_Size2->selectOptionListHtml("x_Component_Display_Size2") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Display_Size2" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Display_Size2->caption() ?>" data-title="<?= $Page->Component_Display_Size2->caption() ?>" data-ew-action="add-option" data-el="x_Component_Display_Size2" data-url="<?= GetUrl("componentdisplaysizeaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Display_Size2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Display_Size2->getErrorMessage() ?></div>
<?= $Page->Component_Display_Size2->Lookup->getParamTag($Page, "p_x_Component_Display_Size2") ?>
<?php if (!$Page->Component_Display_Size2->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Display_Size2", selectId: "fworkstationedit_x_Component_Display_Size2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Display_Size2?.lookupOptions.length) {
        options.data = { id: "x_Component_Display_Size2", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Display_Size2", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Display_Size2.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Display_Size2">
<span<?= $Page->Component_Display_Size2->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Display_Size2->getDisplayValue($Page->Component_Display_Size2->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Display_Size2" data-hidden="1" data-page="5" name="x_Component_Display_Size2" id="x_Component_Display_Size2" value="<?= HtmlEncode($Page->Component_Display_Size2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
    <div id="r_Component_Keyboard_Layout2"<?= $Page->Component_Keyboard_Layout2->rowAttributes() ?>>
        <label id="elh_workstation_Component_Keyboard_Layout2" for="x_Component_Keyboard_Layout2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Component_Keyboard_Layout2->caption() ?><?= $Page->Component_Keyboard_Layout2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Component_Keyboard_Layout2->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_workstation_Component_Keyboard_Layout2">
<div class="input-group flex-nowrap">
    <select
        id="x_Component_Keyboard_Layout2"
        name="x_Component_Keyboard_Layout2"
        class="form-select ew-select<?= $Page->Component_Keyboard_Layout2->isInvalidClass() ?>"
        <?php if (!$Page->Component_Keyboard_Layout2->IsNativeSelect) { ?>
        data-select2-id="fworkstationedit_x_Component_Keyboard_Layout2"
        <?php } ?>
        data-table="workstation"
        data-field="x_Component_Keyboard_Layout2"
        data-page="5"
        data-value-separator="<?= $Page->Component_Keyboard_Layout2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->Component_Keyboard_Layout2->getPlaceHolder()) ?>"
        <?= $Page->Component_Keyboard_Layout2->editAttributes() ?>>
        <?= $Page->Component_Keyboard_Layout2->selectOptionListHtml("x_Component_Keyboard_Layout2") ?>
    </select>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_Component_Keyboard_Layout2" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->Component_Keyboard_Layout2->caption() ?>" data-title="<?= $Page->Component_Keyboard_Layout2->caption() ?>" data-ew-action="add-option" data-el="x_Component_Keyboard_Layout2" data-url="<?= GetUrl("componentkeyboardlayoutaddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
</div>
<?= $Page->Component_Keyboard_Layout2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Component_Keyboard_Layout2->getErrorMessage() ?></div>
<?= $Page->Component_Keyboard_Layout2->Lookup->getParamTag($Page, "p_x_Component_Keyboard_Layout2") ?>
<?php if (!$Page->Component_Keyboard_Layout2->IsNativeSelect) { ?>
<script>
loadjs.ready("fworkstationedit", function() {
    var options = { name: "x_Component_Keyboard_Layout2", selectId: "fworkstationedit_x_Component_Keyboard_Layout2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fworkstationedit.lists.Component_Keyboard_Layout2?.lookupOptions.length) {
        options.data = { id: "x_Component_Keyboard_Layout2", form: "fworkstationedit" };
    } else {
        options.ajax = { id: "x_Component_Keyboard_Layout2", form: "fworkstationedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.workstation.fields.Component_Keyboard_Layout2.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_workstation_Component_Keyboard_Layout2">
<span<?= $Page->Component_Keyboard_Layout2->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->Component_Keyboard_Layout2->getDisplayValue($Page->Component_Keyboard_Layout2->ViewValue) ?></span></span>
<input type="hidden" data-table="workstation" data-field="x_Component_Keyboard_Layout2" data-hidden="1" data-page="5" name="x_Component_Keyboard_Layout2" id="x_Component_Keyboard_Layout2" value="<?= HtmlEncode($Page->Component_Keyboard_Layout2->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
            </div><!-- /multi-page .accordion-body -->
        </div><!-- /multi-page accordion .collapse -->
    </div><!-- /multi-page .accordion-item -->
</div><!-- /multi-page accordion -->
</div><!-- /multi-page -->
    <input type="hidden" data-table="workstation" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fworkstationedit" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fworkstationedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fworkstationedit"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" form="fworkstationedit" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("workstation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
