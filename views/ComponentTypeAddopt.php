<?php

namespace WorkStationDB\project3;

// Page object
$ComponentTypeAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_type: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fcomponent_typeaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_typeaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ComponentType", [fields.ComponentType.visible && fields.ComponentType.required ? ew.Validators.required(fields.ComponentType.caption) : null], fields.ComponentType.isInvalid]
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
<form name="fcomponent_typeaddopt" id="fcomponent_typeaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="component_type">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ComponentType->Visible) { // Component Type ?>
    <div<?= $Page->ComponentType->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ComponentType"><?= $Page->ComponentType->caption() ?><?= $Page->ComponentType->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ComponentType->cellAttributes() ?>>
<input type="<?= $Page->ComponentType->getInputTextType() ?>" name="x_ComponentType" id="x_ComponentType" data-table="component_type" data-field="x_ComponentType" value="<?= $Page->ComponentType->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentType->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentType->formatPattern()) ?>"<?= $Page->ComponentType->editAttributes() ?> aria-describedby="x_ComponentType_help">
<?= $Page->ComponentType->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentType->getErrorMessage() ?></div>
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
    ew.addEventHandlers("component_type");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
