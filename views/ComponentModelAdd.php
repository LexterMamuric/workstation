<?php

namespace WorkStationDB\project3;

// Page object
$ComponentModelAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_model: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcomponent_modeladd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_modeladd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["ComponentModel", [fields.ComponentModel.visible && fields.ComponentModel.required ? ew.Validators.required(fields.ComponentModel.caption) : null], fields.ComponentModel.isInvalid]
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
<form name="fcomponent_modeladd" id="fcomponent_modeladd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="component_model">
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
<?php if ($Page->ComponentModel->Visible) { // Component Model ?>
    <div id="r_ComponentModel"<?= $Page->ComponentModel->rowAttributes() ?>>
        <label id="elh_component_model_ComponentModel" for="x_ComponentModel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ComponentModel->caption() ?><?= $Page->ComponentModel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ComponentModel->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_component_model_ComponentModel">
<input type="<?= $Page->ComponentModel->getInputTextType() ?>" name="x_ComponentModel" id="x_ComponentModel" data-table="component_model" data-field="x_ComponentModel" value="<?= $Page->ComponentModel->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentModel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentModel->formatPattern()) ?>"<?= $Page->ComponentModel->editAttributes() ?> aria-describedby="x_ComponentModel_help">
<?= $Page->ComponentModel->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentModel->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_component_model_ComponentModel">
<span<?= $Page->ComponentModel->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->ComponentModel->getDisplayValue($Page->ComponentModel->ViewValue))) ?>"></span>
<input type="hidden" data-table="component_model" data-field="x_ComponentModel" data-hidden="1" name="x_ComponentModel" id="x_ComponentModel" value="<?= HtmlEncode($Page->ComponentModel->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcomponent_modeladd" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcomponent_modeladd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcomponent_modeladd"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" form="fcomponent_modeladd" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("component_model");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
