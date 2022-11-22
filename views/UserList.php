<?php

namespace WorkStationDB\project3;

// Page object
$UserList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")

        // Dynamic selection lists
        .setLists({
            "User_Email": <?= $Page->User_Email->toClientList($Page) ?>,
            "User_Name": <?= $Page->User_Name->toClientList($Page) ?>,
            "User_Employee_Number": <?= $Page->User_Employee_Number->toClientList($Page) ?>,
            "User_Phone_Number": <?= $Page->User_Phone_Number->toClientList($Page) ?>,
            "Address_Name": <?= $Page->Address_Name->toClientList($Page) ?>,
            "Address_Street": <?= $Page->Address_Street->toClientList($Page) ?>,
            "Address_Zipcode": <?= $Page->Address_Zipcode->toClientList($Page) ?>,
            "Address_City": <?= $Page->Address_City->toClientList($Page) ?>,
            "Address_Country": <?= $Page->Address_Country->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
window.Tabulator || loadjs([
    ew.PATH_BASE + "js/tabulator.min.js?v=19.7.0",
    ew.PATH_BASE + "css/<?= CssFile("tabulator_bootstrap5.css", false) ?>?v=19.7.0"
], "import");
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fusersrch" id="fusersrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fusersrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { user: currentTable } });
var currentForm;
var fusersrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fusersrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["User_Email", [], fields.User_Email.isInvalid],
            ["User_Name", [], fields.User_Name.isInvalid],
            ["User_Employee_Number", [], fields.User_Employee_Number.isInvalid],
            ["User_Phone_Number", [], fields.User_Phone_Number.isInvalid],
            ["Address_Name", [], fields.Address_Name.isInvalid],
            ["Address_Street", [], fields.Address_Street.isInvalid],
            ["Address_Zipcode", [], fields.Address_Zipcode.isInvalid],
            ["Address_City", [], fields.Address_City.isInvalid],
            ["Address_Country", [], fields.Address_Country.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

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
            "User_Email": <?= $Page->User_Email->toClientList($Page) ?>,
            "User_Name": <?= $Page->User_Name->toClientList($Page) ?>,
            "User_Employee_Number": <?= $Page->User_Employee_Number->toClientList($Page) ?>,
            "User_Phone_Number": <?= $Page->User_Phone_Number->toClientList($Page) ?>,
            "Address_Name": <?= $Page->Address_Name->toClientList($Page) ?>,
            "Address_Street": <?= $Page->Address_Street->toClientList($Page) ?>,
            "Address_Zipcode": <?= $Page->Address_Zipcode->toClientList($Page) ?>,
            "Address_City": <?= $Page->Address_City->toClientList($Page) ?>,
            "Address_Country": <?= $Page->Address_Country->toClientList($Page) ?>,
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->User_Email->Visible) { // User_Email ?>
<?php
if (!$Page->User_Email->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_User_Email" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->User_Email->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_User_Email"
            name="x_User_Email[]"
            class="form-control ew-select<?= $Page->User_Email->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_User_Email"
            data-table="user"
            data-field="x_User_Email"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->User_Email->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->User_Email->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->User_Email->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->User_Email->editAttributes() ?>>
            <?= $Page->User_Email->selectOptionListHtml("x_User_Email", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->User_Email->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_User_Email",
                selectId: "fusersrch_x_User_Email",
                ajax: { id: "x_User_Email", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.User_Email.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
<?php
if (!$Page->User_Name->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_User_Name" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->User_Name->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_User_Name"
            name="x_User_Name[]"
            class="form-control ew-select<?= $Page->User_Name->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_User_Name"
            data-table="user"
            data-field="x_User_Name"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->User_Name->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->User_Name->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->User_Name->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->User_Name->editAttributes() ?>>
            <?= $Page->User_Name->selectOptionListHtml("x_User_Name", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->User_Name->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_User_Name",
                selectId: "fusersrch_x_User_Name",
                ajax: { id: "x_User_Name", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.User_Name.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
<?php
if (!$Page->User_Employee_Number->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_User_Employee_Number" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->User_Employee_Number->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_User_Employee_Number"
            name="x_User_Employee_Number[]"
            class="form-control ew-select<?= $Page->User_Employee_Number->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_User_Employee_Number"
            data-table="user"
            data-field="x_User_Employee_Number"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->User_Employee_Number->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->User_Employee_Number->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->User_Employee_Number->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->User_Employee_Number->editAttributes() ?>>
            <?= $Page->User_Employee_Number->selectOptionListHtml("x_User_Employee_Number", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->User_Employee_Number->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_User_Employee_Number",
                selectId: "fusersrch_x_User_Employee_Number",
                ajax: { id: "x_User_Employee_Number", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.User_Employee_Number.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
<?php
if (!$Page->User_Phone_Number->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_User_Phone_Number" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->User_Phone_Number->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_User_Phone_Number"
            name="x_User_Phone_Number[]"
            class="form-control ew-select<?= $Page->User_Phone_Number->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_User_Phone_Number"
            data-table="user"
            data-field="x_User_Phone_Number"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->User_Phone_Number->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->User_Phone_Number->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->User_Phone_Number->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->User_Phone_Number->editAttributes() ?>>
            <?= $Page->User_Phone_Number->selectOptionListHtml("x_User_Phone_Number", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->User_Phone_Number->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_User_Phone_Number",
                selectId: "fusersrch_x_User_Phone_Number",
                ajax: { id: "x_User_Phone_Number", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.User_Phone_Number.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
<?php
if (!$Page->Address_Name->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Address_Name" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Address_Name->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Address_Name"
            name="x_Address_Name[]"
            class="form-control ew-select<?= $Page->Address_Name->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_Address_Name"
            data-table="user"
            data-field="x_Address_Name"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Address_Name->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Address_Name->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Address_Name->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Address_Name->editAttributes() ?>>
            <?= $Page->Address_Name->selectOptionListHtml("x_Address_Name", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Address_Name->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_Address_Name",
                selectId: "fusersrch_x_Address_Name",
                ajax: { id: "x_Address_Name", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.Address_Name.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
<?php
if (!$Page->Address_Street->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Address_Street" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Address_Street->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Address_Street"
            name="x_Address_Street[]"
            class="form-control ew-select<?= $Page->Address_Street->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_Address_Street"
            data-table="user"
            data-field="x_Address_Street"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Address_Street->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Address_Street->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Address_Street->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Address_Street->editAttributes() ?>>
            <?= $Page->Address_Street->selectOptionListHtml("x_Address_Street", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Address_Street->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_Address_Street",
                selectId: "fusersrch_x_Address_Street",
                ajax: { id: "x_Address_Street", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.Address_Street.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
<?php
if (!$Page->Address_Zipcode->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Address_Zipcode" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Address_Zipcode->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Address_Zipcode"
            name="x_Address_Zipcode[]"
            class="form-control ew-select<?= $Page->Address_Zipcode->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_Address_Zipcode"
            data-table="user"
            data-field="x_Address_Zipcode"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Address_Zipcode->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Address_Zipcode->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Address_Zipcode->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Address_Zipcode->editAttributes() ?>>
            <?= $Page->Address_Zipcode->selectOptionListHtml("x_Address_Zipcode", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Address_Zipcode->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_Address_Zipcode",
                selectId: "fusersrch_x_Address_Zipcode",
                ajax: { id: "x_Address_Zipcode", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.Address_Zipcode.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
<?php
if (!$Page->Address_City->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Address_City" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Address_City->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Address_City"
            name="x_Address_City[]"
            class="form-control ew-select<?= $Page->Address_City->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_Address_City"
            data-table="user"
            data-field="x_Address_City"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Address_City->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Address_City->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Address_City->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Address_City->editAttributes() ?>>
            <?= $Page->Address_City->selectOptionListHtml("x_Address_City", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Address_City->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_Address_City",
                selectId: "fusersrch_x_Address_City",
                ajax: { id: "x_Address_City", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.Address_City.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
<?php
if (!$Page->Address_Country->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Address_Country" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Address_Country->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Address_Country"
            name="x_Address_Country[]"
            class="form-control ew-select<?= $Page->Address_Country->isInvalidClass() ?>"
            data-select2-id="fusersrch_x_Address_Country"
            data-table="user"
            data-field="x_Address_Country"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Address_Country->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Address_Country->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Address_Country->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Address_Country->editAttributes() ?>>
            <?= $Page->Address_Country->selectOptionListHtml("x_Address_Country", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Address_Country->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fusersrch", function() {
            var options = {
                name: "x_Address_Country",
                selectId: "fusersrch_x_Address_Country",
                ajax: { id: "x_Address_Country", form: "fusersrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.user.fields.Address_Country.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->SearchColumnCount > 0) { ?>
   <div class="col-sm-auto mb-3">
       <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
   </div>
<?php } ?>
</div><!-- /.row -->
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="user">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_user" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_userlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->User_Email->Visible) { // User_Email ?>
        <th data-name="User_Email" class="<?= $Page->User_Email->headerCellClass() ?>"><div id="elh_user_User_Email" class="user_User_Email"><?= $Page->renderFieldHeader($Page->User_Email) ?></div></th>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
        <th data-name="User_Name" class="<?= $Page->User_Name->headerCellClass() ?>"><div id="elh_user_User_Name" class="user_User_Name"><?= $Page->renderFieldHeader($Page->User_Name) ?></div></th>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
        <th data-name="User_Employee_Number" class="<?= $Page->User_Employee_Number->headerCellClass() ?>"><div id="elh_user_User_Employee_Number" class="user_User_Employee_Number"><?= $Page->renderFieldHeader($Page->User_Employee_Number) ?></div></th>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
        <th data-name="User_Phone_Number" class="<?= $Page->User_Phone_Number->headerCellClass() ?>"><div id="elh_user_User_Phone_Number" class="user_User_Phone_Number"><?= $Page->renderFieldHeader($Page->User_Phone_Number) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
        <th data-name="Address_Name" class="<?= $Page->Address_Name->headerCellClass() ?>"><div id="elh_user_Address_Name" class="user_Address_Name"><?= $Page->renderFieldHeader($Page->Address_Name) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
        <th data-name="Address_Street" class="<?= $Page->Address_Street->headerCellClass() ?>"><div id="elh_user_Address_Street" class="user_Address_Street"><?= $Page->renderFieldHeader($Page->Address_Street) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
        <th data-name="Address_Zipcode" class="<?= $Page->Address_Zipcode->headerCellClass() ?>"><div id="elh_user_Address_Zipcode" class="user_Address_Zipcode"><?= $Page->renderFieldHeader($Page->Address_Zipcode) ?></div></th>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
        <th data-name="Address_City" class="<?= $Page->Address_City->headerCellClass() ?>"><div id="elh_user_Address_City" class="user_Address_City"><?= $Page->renderFieldHeader($Page->Address_City) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
        <th data-name="Address_Country" class="<?= $Page->Address_Country->headerCellClass() ?>"><div id="elh_user_Address_Country" class="user_Address_Country"><?= $Page->renderFieldHeader($Page->Address_Country) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->User_Email->Visible) { // User_Email ?>
        <td data-name="User_Email"<?= $Page->User_Email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_User_Email" class="el_user_User_Email">
<span<?= $Page->User_Email->viewAttributes() ?>>
<?= $Page->User_Email->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Name->Visible) { // User_Name ?>
        <td data-name="User_Name"<?= $Page->User_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_User_Name" class="el_user_User_Name">
<span<?= $Page->User_Name->viewAttributes() ?>>
<?= $Page->User_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
        <td data-name="User_Employee_Number"<?= $Page->User_Employee_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_User_Employee_Number" class="el_user_User_Employee_Number">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<?= $Page->User_Employee_Number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
        <td data-name="User_Phone_Number"<?= $Page->User_Phone_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_User_Phone_Number" class="el_user_User_Phone_Number">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<?= $Page->User_Phone_Number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Name->Visible) { // Address_Name ?>
        <td data-name="Address_Name"<?= $Page->Address_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Address_Name" class="el_user_Address_Name">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<?= $Page->Address_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Street->Visible) { // Address_Street ?>
        <td data-name="Address_Street"<?= $Page->Address_Street->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Address_Street" class="el_user_Address_Street">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<?= $Page->Address_Street->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
        <td data-name="Address_Zipcode"<?= $Page->Address_Zipcode->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Address_Zipcode" class="el_user_Address_Zipcode">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<?= $Page->Address_Zipcode->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_City->Visible) { // Address_City ?>
        <td data-name="Address_City"<?= $Page->Address_City->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Address_City" class="el_user_Address_City">
<span<?= $Page->Address_City->viewAttributes() ?>>
<?= $Page->Address_City->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Country->Visible) { // Address_Country ?>
        <td data-name="Address_Country"<?= $Page->Address_Country->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_user_Address_Country" class="el_user_Address_Country">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<?= $Page->Address_Country->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (
        $Page->Recordset &&
        !$Page->Recordset->EOF &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        (!(($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0))
    ) {
        $Page->Recordset->moveNext();
    }
    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
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
<?php } ?>
