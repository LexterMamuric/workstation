<?php

namespace WorkStationDB\project3;

// Page object
$ComponentDisplaySizeAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_display_size: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fcomponent_display_sizeaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_display_sizeaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ComponentDisplaySize", [fields.ComponentDisplaySize.visible && fields.ComponentDisplaySize.required ? ew.Validators.required(fields.ComponentDisplaySize.caption) : null], fields.ComponentDisplaySize.isInvalid]
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
<form name="fcomponent_display_sizeaddopt" id="fcomponent_display_sizeaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="component_display_size">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ComponentDisplaySize->Visible) { // Component Display Size ?>
    <div<?= $Page->ComponentDisplaySize->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ComponentDisplaySize"><?= $Page->ComponentDisplaySize->caption() ?><?= $Page->ComponentDisplaySize->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ComponentDisplaySize->cellAttributes() ?>>
<input type="<?= $Page->ComponentDisplaySize->getInputTextType() ?>" name="x_ComponentDisplaySize" id="x_ComponentDisplaySize" data-table="component_display_size" data-field="x_ComponentDisplaySize" value="<?= $Page->ComponentDisplaySize->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentDisplaySize->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentDisplaySize->formatPattern()) ?>"<?= $Page->ComponentDisplaySize->editAttributes() ?> aria-describedby="x_ComponentDisplaySize_help">
<?= $Page->ComponentDisplaySize->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentDisplaySize->getErrorMessage() ?></div>
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
    ew.addEventHandlers("component_display_size");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
