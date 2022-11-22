<?php

namespace WorkStationDB\project3;

// Page object
$ComponentCategoryAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_category: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fcomponent_categoryaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_categoryaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ComponentCategory", [fields.ComponentCategory.visible && fields.ComponentCategory.required ? ew.Validators.required(fields.ComponentCategory.caption) : null], fields.ComponentCategory.isInvalid]
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
<form name="fcomponent_categoryaddopt" id="fcomponent_categoryaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="component_category">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ComponentCategory->Visible) { // Component Category ?>
    <div<?= $Page->ComponentCategory->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ComponentCategory"><?= $Page->ComponentCategory->caption() ?><?= $Page->ComponentCategory->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ComponentCategory->cellAttributes() ?>>
<input type="<?= $Page->ComponentCategory->getInputTextType() ?>" name="x_ComponentCategory" id="x_ComponentCategory" data-table="component_category" data-field="x_ComponentCategory" value="<?= $Page->ComponentCategory->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentCategory->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentCategory->formatPattern()) ?>"<?= $Page->ComponentCategory->editAttributes() ?> aria-describedby="x_ComponentCategory_help">
<?= $Page->ComponentCategory->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentCategory->getErrorMessage() ?></div>
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
    ew.addEventHandlers("component_category");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
