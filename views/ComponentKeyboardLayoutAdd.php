<?php

namespace WorkStationDB\project3;

// Page object
$ComponentKeyboardLayoutAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_keyboard_layout: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcomponent_keyboard_layoutadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_keyboard_layoutadd")
        .setPageId("add")

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
<?php
$Page->showMessage();
?>
<form name="fcomponent_keyboard_layoutadd" id="fcomponent_keyboard_layoutadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="component_keyboard_layout">
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
<?php if ($Page->ComponentKeyboardLayout->Visible) { // Component Keyboard Layout ?>
    <div id="r_ComponentKeyboardLayout"<?= $Page->ComponentKeyboardLayout->rowAttributes() ?>>
        <label id="elh_component_keyboard_layout_ComponentKeyboardLayout" for="x_ComponentKeyboardLayout" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ComponentKeyboardLayout->caption() ?><?= $Page->ComponentKeyboardLayout->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ComponentKeyboardLayout->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_component_keyboard_layout_ComponentKeyboardLayout">
<input type="<?= $Page->ComponentKeyboardLayout->getInputTextType() ?>" name="x_ComponentKeyboardLayout" id="x_ComponentKeyboardLayout" data-table="component_keyboard_layout" data-field="x_ComponentKeyboardLayout" value="<?= $Page->ComponentKeyboardLayout->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentKeyboardLayout->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentKeyboardLayout->formatPattern()) ?>"<?= $Page->ComponentKeyboardLayout->editAttributes() ?> aria-describedby="x_ComponentKeyboardLayout_help">
<?= $Page->ComponentKeyboardLayout->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentKeyboardLayout->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_component_keyboard_layout_ComponentKeyboardLayout">
<span<?= $Page->ComponentKeyboardLayout->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->ComponentKeyboardLayout->getDisplayValue($Page->ComponentKeyboardLayout->ViewValue))) ?>"></span>
<input type="hidden" data-table="component_keyboard_layout" data-field="x_ComponentKeyboardLayout" data-hidden="1" name="x_ComponentKeyboardLayout" id="x_ComponentKeyboardLayout" value="<?= HtmlEncode($Page->ComponentKeyboardLayout->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcomponent_keyboard_layoutadd" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcomponent_keyboard_layoutadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcomponent_keyboard_layoutadd"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" form="fcomponent_keyboard_layoutadd" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("component_keyboard_layout");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
