<?php

namespace WorkStationDB\project3;

// Page object
$ComponentKeyboardLayoutAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_keyboard_layout: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fcomponent_keyboard_layoutaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_keyboard_layoutaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ComponentKeyboardLayout", [fields.ComponentKeyboardLayout.visible && fields.ComponentKeyboardLayout.required ? ew.Validators.required(fields.ComponentKeyboardLayout.caption) : null], fields.ComponentKeyboardLayout.isInvalid]
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
<form name="fcomponent_keyboard_layoutaddopt" id="fcomponent_keyboard_layoutaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="component_keyboard_layout">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ComponentKeyboardLayout->Visible) { // Component Keyboard Layout ?>
    <div<?= $Page->ComponentKeyboardLayout->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ComponentKeyboardLayout"><?= $Page->ComponentKeyboardLayout->caption() ?><?= $Page->ComponentKeyboardLayout->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ComponentKeyboardLayout->cellAttributes() ?>>
<input type="<?= $Page->ComponentKeyboardLayout->getInputTextType() ?>" name="x_ComponentKeyboardLayout" id="x_ComponentKeyboardLayout" data-table="component_keyboard_layout" data-field="x_ComponentKeyboardLayout" value="<?= $Page->ComponentKeyboardLayout->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentKeyboardLayout->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentKeyboardLayout->formatPattern()) ?>"<?= $Page->ComponentKeyboardLayout->editAttributes() ?> aria-describedby="x_ComponentKeyboardLayout_help">
<?= $Page->ComponentKeyboardLayout->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentKeyboardLayout->getErrorMessage() ?></div>
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
    ew.addEventHandlers("component_keyboard_layout");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
