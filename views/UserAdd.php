<?php

namespace WorkStationDB\project3;

// Page object
$UserAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fuseradd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fuseradd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["User_Email", [fields.User_Email.visible && fields.User_Email.required ? ew.Validators.required(fields.User_Email.caption) : null], fields.User_Email.isInvalid],
            ["User_Name", [fields.User_Name.visible && fields.User_Name.required ? ew.Validators.required(fields.User_Name.caption) : null], fields.User_Name.isInvalid],
            ["User_Employee_Number", [fields.User_Employee_Number.visible && fields.User_Employee_Number.required ? ew.Validators.required(fields.User_Employee_Number.caption) : null], fields.User_Employee_Number.isInvalid],
            ["User_Phone_Number", [fields.User_Phone_Number.visible && fields.User_Phone_Number.required ? ew.Validators.required(fields.User_Phone_Number.caption) : null], fields.User_Phone_Number.isInvalid],
            ["Address_Name", [fields.Address_Name.visible && fields.Address_Name.required ? ew.Validators.required(fields.Address_Name.caption) : null], fields.Address_Name.isInvalid],
            ["Address_Street", [fields.Address_Street.visible && fields.Address_Street.required ? ew.Validators.required(fields.Address_Street.caption) : null], fields.Address_Street.isInvalid],
            ["Address_Zipcode", [fields.Address_Zipcode.visible && fields.Address_Zipcode.required ? ew.Validators.required(fields.Address_Zipcode.caption) : null], fields.Address_Zipcode.isInvalid],
            ["Address_City", [fields.Address_City.visible && fields.Address_City.required ? ew.Validators.required(fields.Address_City.caption) : null], fields.Address_City.isInvalid],
            ["Address_Country", [fields.Address_Country.visible && fields.Address_Country.required ? ew.Validators.required(fields.Address_Country.caption) : null], fields.Address_Country.isInvalid]
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

        // Dynamic selection lists
        .setLists({
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fuseradd" id="fuseradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="user">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->User_Email->Visible) { // User_Email ?>
    <div id="r_User_Email"<?= $Page->User_Email->rowAttributes() ?>>
        <label id="elh_user_User_Email" for="x_User_Email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Email->caption() ?><?= $Page->User_Email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Email->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_User_Email">
<input type="<?= $Page->User_Email->getInputTextType() ?>" name="x_User_Email" id="x_User_Email" data-table="user" data-field="x_User_Email" value="<?= $Page->User_Email->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Email->formatPattern()) ?>"<?= $Page->User_Email->editAttributes() ?> aria-describedby="x_User_Email_help">
<?= $Page->User_Email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Email->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_User_Email">
<span<?= $Page->User_Email->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Email->getDisplayValue($Page->User_Email->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_User_Email" data-hidden="1" name="x_User_Email" id="x_User_Email" value="<?= HtmlEncode($Page->User_Email->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
    <div id="r_User_Name"<?= $Page->User_Name->rowAttributes() ?>>
        <label id="elh_user_User_Name" for="x_User_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Name->caption() ?><?= $Page->User_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_User_Name">
<input type="<?= $Page->User_Name->getInputTextType() ?>" name="x_User_Name" id="x_User_Name" data-table="user" data-field="x_User_Name" value="<?= $Page->User_Name->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Name->formatPattern()) ?>"<?= $Page->User_Name->editAttributes() ?> aria-describedby="x_User_Name_help">
<?= $Page->User_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_User_Name">
<span<?= $Page->User_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Name->getDisplayValue($Page->User_Name->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_User_Name" data-hidden="1" name="x_User_Name" id="x_User_Name" value="<?= HtmlEncode($Page->User_Name->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
    <div id="r_User_Employee_Number"<?= $Page->User_Employee_Number->rowAttributes() ?>>
        <label id="elh_user_User_Employee_Number" for="x_User_Employee_Number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Employee_Number->caption() ?><?= $Page->User_Employee_Number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Employee_Number->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_User_Employee_Number">
<input type="<?= $Page->User_Employee_Number->getInputTextType() ?>" name="x_User_Employee_Number" id="x_User_Employee_Number" data-table="user" data-field="x_User_Employee_Number" value="<?= $Page->User_Employee_Number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Employee_Number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Employee_Number->formatPattern()) ?>"<?= $Page->User_Employee_Number->editAttributes() ?> aria-describedby="x_User_Employee_Number_help">
<?= $Page->User_Employee_Number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Employee_Number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_User_Employee_Number">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Employee_Number->getDisplayValue($Page->User_Employee_Number->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_User_Employee_Number" data-hidden="1" name="x_User_Employee_Number" id="x_User_Employee_Number" value="<?= HtmlEncode($Page->User_Employee_Number->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
    <div id="r_User_Phone_Number"<?= $Page->User_Phone_Number->rowAttributes() ?>>
        <label id="elh_user_User_Phone_Number" for="x_User_Phone_Number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->User_Phone_Number->caption() ?><?= $Page->User_Phone_Number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->User_Phone_Number->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_User_Phone_Number">
<input type="<?= $Page->User_Phone_Number->getInputTextType() ?>" name="x_User_Phone_Number" id="x_User_Phone_Number" data-table="user" data-field="x_User_Phone_Number" value="<?= $Page->User_Phone_Number->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->User_Phone_Number->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->User_Phone_Number->formatPattern()) ?>"<?= $Page->User_Phone_Number->editAttributes() ?> aria-describedby="x_User_Phone_Number_help">
<?= $Page->User_Phone_Number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->User_Phone_Number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_User_Phone_Number">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->User_Phone_Number->getDisplayValue($Page->User_Phone_Number->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_User_Phone_Number" data-hidden="1" name="x_User_Phone_Number" id="x_User_Phone_Number" value="<?= HtmlEncode($Page->User_Phone_Number->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
    <div id="r_Address_Name"<?= $Page->Address_Name->rowAttributes() ?>>
        <label id="elh_user_Address_Name" for="x_Address_Name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Name->caption() ?><?= $Page->Address_Name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_Address_Name">
<input type="<?= $Page->Address_Name->getInputTextType() ?>" name="x_Address_Name" id="x_Address_Name" data-table="user" data-field="x_Address_Name" value="<?= $Page->Address_Name->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Name->formatPattern()) ?>"<?= $Page->Address_Name->editAttributes() ?> aria-describedby="x_Address_Name_help">
<?= $Page->Address_Name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_Address_Name">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Name->getDisplayValue($Page->Address_Name->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_Address_Name" data-hidden="1" name="x_Address_Name" id="x_Address_Name" value="<?= HtmlEncode($Page->Address_Name->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
    <div id="r_Address_Street"<?= $Page->Address_Street->rowAttributes() ?>>
        <label id="elh_user_Address_Street" for="x_Address_Street" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Street->caption() ?><?= $Page->Address_Street->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Street->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_Address_Street">
<input type="<?= $Page->Address_Street->getInputTextType() ?>" name="x_Address_Street" id="x_Address_Street" data-table="user" data-field="x_Address_Street" value="<?= $Page->Address_Street->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Street->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Street->formatPattern()) ?>"<?= $Page->Address_Street->editAttributes() ?> aria-describedby="x_Address_Street_help">
<?= $Page->Address_Street->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Street->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_Address_Street">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Street->getDisplayValue($Page->Address_Street->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_Address_Street" data-hidden="1" name="x_Address_Street" id="x_Address_Street" value="<?= HtmlEncode($Page->Address_Street->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
    <div id="r_Address_Zipcode"<?= $Page->Address_Zipcode->rowAttributes() ?>>
        <label id="elh_user_Address_Zipcode" for="x_Address_Zipcode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Zipcode->caption() ?><?= $Page->Address_Zipcode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Zipcode->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_Address_Zipcode">
<input type="<?= $Page->Address_Zipcode->getInputTextType() ?>" name="x_Address_Zipcode" id="x_Address_Zipcode" data-table="user" data-field="x_Address_Zipcode" value="<?= $Page->Address_Zipcode->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Zipcode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Zipcode->formatPattern()) ?>"<?= $Page->Address_Zipcode->editAttributes() ?> aria-describedby="x_Address_Zipcode_help">
<?= $Page->Address_Zipcode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Zipcode->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_Address_Zipcode">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Zipcode->getDisplayValue($Page->Address_Zipcode->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_Address_Zipcode" data-hidden="1" name="x_Address_Zipcode" id="x_Address_Zipcode" value="<?= HtmlEncode($Page->Address_Zipcode->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
    <div id="r_Address_City"<?= $Page->Address_City->rowAttributes() ?>>
        <label id="elh_user_Address_City" for="x_Address_City" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_City->caption() ?><?= $Page->Address_City->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_City->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_Address_City">
<input type="<?= $Page->Address_City->getInputTextType() ?>" name="x_Address_City" id="x_Address_City" data-table="user" data-field="x_Address_City" value="<?= $Page->Address_City->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_City->formatPattern()) ?>"<?= $Page->Address_City->editAttributes() ?> aria-describedby="x_Address_City_help">
<?= $Page->Address_City->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_City->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_Address_City">
<span<?= $Page->Address_City->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_City->getDisplayValue($Page->Address_City->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_Address_City" data-hidden="1" name="x_Address_City" id="x_Address_City" value="<?= HtmlEncode($Page->Address_City->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
    <div id="r_Address_Country"<?= $Page->Address_Country->rowAttributes() ?>>
        <label id="elh_user_Address_Country" for="x_Address_Country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address_Country->caption() ?><?= $Page->Address_Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address_Country->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_user_Address_Country">
<input type="<?= $Page->Address_Country->getInputTextType() ?>" name="x_Address_Country" id="x_Address_Country" data-table="user" data-field="x_Address_Country" value="<?= $Page->Address_Country->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->Address_Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address_Country->formatPattern()) ?>"<?= $Page->Address_Country->editAttributes() ?> aria-describedby="x_Address_Country_help">
<?= $Page->Address_Country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address_Country->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_user_Address_Country">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Address_Country->getDisplayValue($Page->Address_Country->ViewValue))) ?>"></span>
<input type="hidden" data-table="user" data-field="x_Address_Country" data-hidden="1" name="x_Address_Country" id="x_Address_Country" value="<?= HtmlEncode($Page->Address_Country->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fuseradd" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fuseradd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fuseradd"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" form="fuseradd" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("user");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
