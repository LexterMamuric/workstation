<?php

namespace WorkStationDB\project3;

// Page object
$ComponentModelAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_model: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fcomponent_modeladdopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_modeladdopt")
        .setPageId("addopt")

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
<form name="fcomponent_modeladdopt" id="fcomponent_modeladdopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="component_model">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ComponentModel->Visible) { // Component Model ?>
    <div<?= $Page->ComponentModel->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ComponentModel"><?= $Page->ComponentModel->caption() ?><?= $Page->ComponentModel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ComponentModel->cellAttributes() ?>>
<input type="<?= $Page->ComponentModel->getInputTextType() ?>" name="x_ComponentModel" id="x_ComponentModel" data-table="component_model" data-field="x_ComponentModel" value="<?= $Page->ComponentModel->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentModel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentModel->formatPattern()) ?>"<?= $Page->ComponentModel->editAttributes() ?> aria-describedby="x_ComponentModel_help">
<?= $Page->ComponentModel->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentModel->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
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
