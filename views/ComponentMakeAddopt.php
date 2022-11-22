<?php

namespace WorkStationDB\project3;

// Page object
$ComponentMakeAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_make: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fcomponent_makeaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_makeaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ComponentMake", [fields.ComponentMake.visible && fields.ComponentMake.required ? ew.Validators.required(fields.ComponentMake.caption) : null], fields.ComponentMake.isInvalid]
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
<form name="fcomponent_makeaddopt" id="fcomponent_makeaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="component_make">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ComponentMake->Visible) { // Component Make ?>
    <div<?= $Page->ComponentMake->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ComponentMake"><?= $Page->ComponentMake->caption() ?><?= $Page->ComponentMake->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ComponentMake->cellAttributes() ?>>
<input type="<?= $Page->ComponentMake->getInputTextType() ?>" name="x_ComponentMake" id="x_ComponentMake" data-table="component_make" data-field="x_ComponentMake" value="<?= $Page->ComponentMake->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentMake->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentMake->formatPattern()) ?>"<?= $Page->ComponentMake->editAttributes() ?> aria-describedby="x_ComponentMake_help">
<?= $Page->ComponentMake->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentMake->getErrorMessage() ?></div>
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
    ew.addEventHandlers("component_make");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
