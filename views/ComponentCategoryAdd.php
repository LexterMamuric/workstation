<?php

namespace WorkStationDB\project3;

// Page object
$ComponentCategoryAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { component_category: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcomponent_categoryadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcomponent_categoryadd")
        .setPageId("add")

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
<?php
$Page->showMessage();
?>
<form name="fcomponent_categoryadd" id="fcomponent_categoryadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="component_category">
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
<?php if ($Page->ComponentCategory->Visible) { // Component Category ?>
    <div id="r_ComponentCategory"<?= $Page->ComponentCategory->rowAttributes() ?>>
        <label id="elh_component_category_ComponentCategory" for="x_ComponentCategory" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ComponentCategory->caption() ?><?= $Page->ComponentCategory->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ComponentCategory->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_component_category_ComponentCategory">
<input type="<?= $Page->ComponentCategory->getInputTextType() ?>" name="x_ComponentCategory" id="x_ComponentCategory" data-table="component_category" data-field="x_ComponentCategory" value="<?= $Page->ComponentCategory->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->ComponentCategory->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ComponentCategory->formatPattern()) ?>"<?= $Page->ComponentCategory->editAttributes() ?> aria-describedby="x_ComponentCategory_help">
<?= $Page->ComponentCategory->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ComponentCategory->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_component_category_ComponentCategory">
<span<?= $Page->ComponentCategory->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->ComponentCategory->getDisplayValue($Page->ComponentCategory->ViewValue))) ?>"></span>
<input type="hidden" data-table="component_category" data-field="x_ComponentCategory" data-hidden="1" name="x_ComponentCategory" id="x_ComponentCategory" value="<?= HtmlEncode($Page->ComponentCategory->FormValue) ?>">
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcomponent_categoryadd" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcomponent_categoryadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcomponent_categoryadd"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" form="fcomponent_categoryadd" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("component_category");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
