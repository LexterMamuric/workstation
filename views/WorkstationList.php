<?php

namespace WorkStationDB\project3;

// Page object
$WorkstationList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { workstation: currentTable } });
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
            "Workstation_Name": <?= $Page->Workstation_Name->toClientList($Page) ?>,
            "Workstation_Remark": <?= $Page->Workstation_Remark->toClientList($Page) ?>,
            "User_Email": <?= $Page->User_Email->toClientList($Page) ?>,
            "User_Name": <?= $Page->User_Name->toClientList($Page) ?>,
            "User_Employee_Number": <?= $Page->User_Employee_Number->toClientList($Page) ?>,
            "User_Phone_Number": <?= $Page->User_Phone_Number->toClientList($Page) ?>,
            "Address_Name": <?= $Page->Address_Name->toClientList($Page) ?>,
            "Address_Street": <?= $Page->Address_Street->toClientList($Page) ?>,
            "Address_Zipcode": <?= $Page->Address_Zipcode->toClientList($Page) ?>,
            "Address_City": <?= $Page->Address_City->toClientList($Page) ?>,
            "Address_Country": <?= $Page->Address_Country->toClientList($Page) ?>,
            "Component_Type": <?= $Page->Component_Type->toClientList($Page) ?>,
            "Component_Category": <?= $Page->Component_Category->toClientList($Page) ?>,
            "Component_Make": <?= $Page->Component_Make->toClientList($Page) ?>,
            "Component_Model": <?= $Page->Component_Model->toClientList($Page) ?>,
            "Component_Serial_Number": <?= $Page->Component_Serial_Number->toClientList($Page) ?>,
            "Component_Display_Size": <?= $Page->Component_Display_Size->toClientList($Page) ?>,
            "Component_Keyboard_Layout": <?= $Page->Component_Keyboard_Layout->toClientList($Page) ?>,
            "Component_Type1": <?= $Page->Component_Type1->toClientList($Page) ?>,
            "Component_Category1": <?= $Page->Component_Category1->toClientList($Page) ?>,
            "Component_Make1": <?= $Page->Component_Make1->toClientList($Page) ?>,
            "Component_Model1": <?= $Page->Component_Model1->toClientList($Page) ?>,
            "Component_Serial_Number1": <?= $Page->Component_Serial_Number1->toClientList($Page) ?>,
            "Component_Display_Size1": <?= $Page->Component_Display_Size1->toClientList($Page) ?>,
            "Component_Keyboard_Layout1": <?= $Page->Component_Keyboard_Layout1->toClientList($Page) ?>,
            "Component_Type2": <?= $Page->Component_Type2->toClientList($Page) ?>,
            "Component_Category2": <?= $Page->Component_Category2->toClientList($Page) ?>,
            "Component_Make2": <?= $Page->Component_Make2->toClientList($Page) ?>,
            "Component_Model2": <?= $Page->Component_Model2->toClientList($Page) ?>,
            "Component_Serial_Number2": <?= $Page->Component_Serial_Number2->toClientList($Page) ?>,
            "Component_Display_Size2": <?= $Page->Component_Display_Size2->toClientList($Page) ?>,
            "Component_Keyboard_Layout2": <?= $Page->Component_Keyboard_Layout2->toClientList($Page) ?>,
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
<form name="fworkstationsrch" id="fworkstationsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="on">
<div id="fworkstationsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { workstation: currentTable } });
var currentForm;
var fworkstationsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fworkstationsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["Workstation_Name", [], fields.Workstation_Name.isInvalid],
            ["Workstation_Remark", [], fields.Workstation_Remark.isInvalid],
            ["User_Email", [], fields.User_Email.isInvalid],
            ["User_Name", [], fields.User_Name.isInvalid],
            ["User_Employee_Number", [], fields.User_Employee_Number.isInvalid],
            ["User_Phone_Number", [], fields.User_Phone_Number.isInvalid],
            ["Address_Name", [], fields.Address_Name.isInvalid],
            ["Address_Street", [], fields.Address_Street.isInvalid],
            ["Address_Zipcode", [], fields.Address_Zipcode.isInvalid],
            ["Address_City", [], fields.Address_City.isInvalid],
            ["Address_Country", [], fields.Address_Country.isInvalid],
            ["Component_Type", [], fields.Component_Type.isInvalid],
            ["Component_Category", [], fields.Component_Category.isInvalid],
            ["Component_Make", [], fields.Component_Make.isInvalid],
            ["Component_Model", [], fields.Component_Model.isInvalid],
            ["Component_Serial_Number", [], fields.Component_Serial_Number.isInvalid],
            ["Component_Display_Size", [], fields.Component_Display_Size.isInvalid],
            ["Component_Keyboard_Layout", [], fields.Component_Keyboard_Layout.isInvalid],
            ["Component_Type1", [], fields.Component_Type1.isInvalid],
            ["Component_Category1", [], fields.Component_Category1.isInvalid],
            ["Component_Make1", [], fields.Component_Make1.isInvalid],
            ["Component_Model1", [], fields.Component_Model1.isInvalid],
            ["Component_Serial_Number1", [], fields.Component_Serial_Number1.isInvalid],
            ["Component_Display_Size1", [], fields.Component_Display_Size1.isInvalid],
            ["Component_Keyboard_Layout1", [], fields.Component_Keyboard_Layout1.isInvalid],
            ["Component_Type2", [], fields.Component_Type2.isInvalid],
            ["Component_Category2", [], fields.Component_Category2.isInvalid],
            ["Component_Make2", [], fields.Component_Make2.isInvalid],
            ["Component_Model2", [], fields.Component_Model2.isInvalid],
            ["Component_Serial_Number2", [], fields.Component_Serial_Number2.isInvalid],
            ["Component_Display_Size2", [], fields.Component_Display_Size2.isInvalid],
            ["Component_Keyboard_Layout2", [], fields.Component_Keyboard_Layout2.isInvalid]
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
            "Workstation_Name": <?= $Page->Workstation_Name->toClientList($Page) ?>,
            "Workstation_Remark": <?= $Page->Workstation_Remark->toClientList($Page) ?>,
            "User_Email": <?= $Page->User_Email->toClientList($Page) ?>,
            "User_Name": <?= $Page->User_Name->toClientList($Page) ?>,
            "User_Employee_Number": <?= $Page->User_Employee_Number->toClientList($Page) ?>,
            "User_Phone_Number": <?= $Page->User_Phone_Number->toClientList($Page) ?>,
            "Address_Name": <?= $Page->Address_Name->toClientList($Page) ?>,
            "Address_Street": <?= $Page->Address_Street->toClientList($Page) ?>,
            "Address_Zipcode": <?= $Page->Address_Zipcode->toClientList($Page) ?>,
            "Address_City": <?= $Page->Address_City->toClientList($Page) ?>,
            "Address_Country": <?= $Page->Address_Country->toClientList($Page) ?>,
            "Component_Type": <?= $Page->Component_Type->toClientList($Page) ?>,
            "Component_Category": <?= $Page->Component_Category->toClientList($Page) ?>,
            "Component_Make": <?= $Page->Component_Make->toClientList($Page) ?>,
            "Component_Model": <?= $Page->Component_Model->toClientList($Page) ?>,
            "Component_Serial_Number": <?= $Page->Component_Serial_Number->toClientList($Page) ?>,
            "Component_Display_Size": <?= $Page->Component_Display_Size->toClientList($Page) ?>,
            "Component_Keyboard_Layout": <?= $Page->Component_Keyboard_Layout->toClientList($Page) ?>,
            "Component_Type1": <?= $Page->Component_Type1->toClientList($Page) ?>,
            "Component_Category1": <?= $Page->Component_Category1->toClientList($Page) ?>,
            "Component_Make1": <?= $Page->Component_Make1->toClientList($Page) ?>,
            "Component_Model1": <?= $Page->Component_Model1->toClientList($Page) ?>,
            "Component_Serial_Number1": <?= $Page->Component_Serial_Number1->toClientList($Page) ?>,
            "Component_Display_Size1": <?= $Page->Component_Display_Size1->toClientList($Page) ?>,
            "Component_Keyboard_Layout1": <?= $Page->Component_Keyboard_Layout1->toClientList($Page) ?>,
            "Component_Type2": <?= $Page->Component_Type2->toClientList($Page) ?>,
            "Component_Category2": <?= $Page->Component_Category2->toClientList($Page) ?>,
            "Component_Make2": <?= $Page->Component_Make2->toClientList($Page) ?>,
            "Component_Model2": <?= $Page->Component_Model2->toClientList($Page) ?>,
            "Component_Serial_Number2": <?= $Page->Component_Serial_Number2->toClientList($Page) ?>,
            "Component_Display_Size2": <?= $Page->Component_Display_Size2->toClientList($Page) ?>,
            "Component_Keyboard_Layout2": <?= $Page->Component_Keyboard_Layout2->toClientList($Page) ?>,
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
<?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
<?php
if (!$Page->Workstation_Name->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Workstation_Name" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Workstation_Name->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Workstation_Name"
            name="x_Workstation_Name[]"
            class="form-control ew-select<?= $Page->Workstation_Name->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Workstation_Name"
            data-table="workstation"
            data-field="x_Workstation_Name"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Workstation_Name->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Workstation_Name->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Workstation_Name->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Workstation_Name->editAttributes() ?>>
            <?= $Page->Workstation_Name->selectOptionListHtml("x_Workstation_Name", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Workstation_Name->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Workstation_Name",
                selectId: "fworkstationsrch_x_Workstation_Name",
                ajax: { id: "x_Workstation_Name", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Workstation_Name.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
<?php
if (!$Page->Workstation_Remark->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Workstation_Remark" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Workstation_Remark->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Workstation_Remark"
            name="x_Workstation_Remark[]"
            class="form-control ew-select<?= $Page->Workstation_Remark->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Workstation_Remark"
            data-table="workstation"
            data-field="x_Workstation_Remark"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Workstation_Remark->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Workstation_Remark->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Workstation_Remark->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Workstation_Remark->editAttributes() ?>>
            <?= $Page->Workstation_Remark->selectOptionListHtml("x_Workstation_Remark", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Workstation_Remark->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Workstation_Remark",
                selectId: "fworkstationsrch_x_Workstation_Remark",
                ajax: { id: "x_Workstation_Remark", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Workstation_Remark.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
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
            data-select2-id="fworkstationsrch_x_User_Email"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_User_Email",
                selectId: "fworkstationsrch_x_User_Email",
                ajax: { id: "x_User_Email", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.User_Email.filterOptions);
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
            data-select2-id="fworkstationsrch_x_User_Name"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_User_Name",
                selectId: "fworkstationsrch_x_User_Name",
                ajax: { id: "x_User_Name", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.User_Name.filterOptions);
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
            data-select2-id="fworkstationsrch_x_User_Employee_Number"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_User_Employee_Number",
                selectId: "fworkstationsrch_x_User_Employee_Number",
                ajax: { id: "x_User_Employee_Number", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.User_Employee_Number.filterOptions);
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
            data-select2-id="fworkstationsrch_x_User_Phone_Number"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_User_Phone_Number",
                selectId: "fworkstationsrch_x_User_Phone_Number",
                ajax: { id: "x_User_Phone_Number", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.User_Phone_Number.filterOptions);
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
            data-select2-id="fworkstationsrch_x_Address_Name"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Address_Name",
                selectId: "fworkstationsrch_x_Address_Name",
                ajax: { id: "x_Address_Name", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Address_Name.filterOptions);
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
            data-select2-id="fworkstationsrch_x_Address_Street"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Address_Street",
                selectId: "fworkstationsrch_x_Address_Street",
                ajax: { id: "x_Address_Street", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Address_Street.filterOptions);
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
            data-select2-id="fworkstationsrch_x_Address_Zipcode"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Address_Zipcode",
                selectId: "fworkstationsrch_x_Address_Zipcode",
                ajax: { id: "x_Address_Zipcode", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Address_Zipcode.filterOptions);
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
            data-select2-id="fworkstationsrch_x_Address_City"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Address_City",
                selectId: "fworkstationsrch_x_Address_City",
                ajax: { id: "x_Address_City", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Address_City.filterOptions);
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
            data-select2-id="fworkstationsrch_x_Address_Country"
            data-table="workstation"
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
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Address_Country",
                selectId: "fworkstationsrch_x_Address_Country",
                ajax: { id: "x_Address_Country", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Address_Country.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Type->Visible) { // Component_Type ?>
<?php
if (!$Page->Component_Type->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Type" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Type->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Type"
            name="x_Component_Type[]"
            class="form-control ew-select<?= $Page->Component_Type->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Type"
            data-table="workstation"
            data-field="x_Component_Type"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Type->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Type->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Type->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Type->editAttributes() ?>>
            <?= $Page->Component_Type->selectOptionListHtml("x_Component_Type", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Type->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Type",
                selectId: "fworkstationsrch_x_Component_Type",
                ajax: { id: "x_Component_Type", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Type.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Category->Visible) { // Component_Category ?>
<?php
if (!$Page->Component_Category->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Category" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Category->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Category"
            name="x_Component_Category[]"
            class="form-control ew-select<?= $Page->Component_Category->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Category"
            data-table="workstation"
            data-field="x_Component_Category"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Category->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Category->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Category->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Category->editAttributes() ?>>
            <?= $Page->Component_Category->selectOptionListHtml("x_Component_Category", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Category->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Category",
                selectId: "fworkstationsrch_x_Component_Category",
                ajax: { id: "x_Component_Category", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Category.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Make->Visible) { // Component_Make ?>
<?php
if (!$Page->Component_Make->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Make" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Make->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Make"
            name="x_Component_Make[]"
            class="form-control ew-select<?= $Page->Component_Make->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Make"
            data-table="workstation"
            data-field="x_Component_Make"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Make->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Make->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Make->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Make->editAttributes() ?>>
            <?= $Page->Component_Make->selectOptionListHtml("x_Component_Make", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Make->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Make",
                selectId: "fworkstationsrch_x_Component_Make",
                ajax: { id: "x_Component_Make", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Make.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Model->Visible) { // Component_Model ?>
<?php
if (!$Page->Component_Model->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Model" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Model->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Model"
            name="x_Component_Model[]"
            class="form-control ew-select<?= $Page->Component_Model->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Model"
            data-table="workstation"
            data-field="x_Component_Model"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Model->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Model->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Model->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Model->editAttributes() ?>>
            <?= $Page->Component_Model->selectOptionListHtml("x_Component_Model", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Model->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Model",
                selectId: "fworkstationsrch_x_Component_Model",
                ajax: { id: "x_Component_Model", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Model.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
<?php
if (!$Page->Component_Serial_Number->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Serial_Number" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Serial_Number->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Serial_Number"
            name="x_Component_Serial_Number[]"
            class="form-control ew-select<?= $Page->Component_Serial_Number->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Serial_Number"
            data-table="workstation"
            data-field="x_Component_Serial_Number"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Serial_Number->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Serial_Number->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Serial_Number->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Serial_Number->editAttributes() ?>>
            <?= $Page->Component_Serial_Number->selectOptionListHtml("x_Component_Serial_Number", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Serial_Number->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Serial_Number",
                selectId: "fworkstationsrch_x_Component_Serial_Number",
                ajax: { id: "x_Component_Serial_Number", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Serial_Number.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
<?php
if (!$Page->Component_Display_Size->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Display_Size" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Display_Size->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Display_Size"
            name="x_Component_Display_Size[]"
            class="form-control ew-select<?= $Page->Component_Display_Size->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Display_Size"
            data-table="workstation"
            data-field="x_Component_Display_Size"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Display_Size->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Display_Size->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Display_Size->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Display_Size->editAttributes() ?>>
            <?= $Page->Component_Display_Size->selectOptionListHtml("x_Component_Display_Size", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Display_Size->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Display_Size",
                selectId: "fworkstationsrch_x_Component_Display_Size",
                ajax: { id: "x_Component_Display_Size", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Display_Size.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
<?php
if (!$Page->Component_Keyboard_Layout->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Keyboard_Layout" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Keyboard_Layout->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Keyboard_Layout"
            name="x_Component_Keyboard_Layout[]"
            class="form-control ew-select<?= $Page->Component_Keyboard_Layout->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Keyboard_Layout"
            data-table="workstation"
            data-field="x_Component_Keyboard_Layout"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Keyboard_Layout->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Keyboard_Layout->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Keyboard_Layout->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Keyboard_Layout->editAttributes() ?>>
            <?= $Page->Component_Keyboard_Layout->selectOptionListHtml("x_Component_Keyboard_Layout", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Keyboard_Layout->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Keyboard_Layout",
                selectId: "fworkstationsrch_x_Component_Keyboard_Layout",
                ajax: { id: "x_Component_Keyboard_Layout", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Keyboard_Layout.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
<?php
if (!$Page->Component_Type1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Type1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Type1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Type1"
            name="x_Component_Type1[]"
            class="form-control ew-select<?= $Page->Component_Type1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Type1"
            data-table="workstation"
            data-field="x_Component_Type1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Type1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Type1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Type1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Type1->editAttributes() ?>>
            <?= $Page->Component_Type1->selectOptionListHtml("x_Component_Type1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Type1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Type1",
                selectId: "fworkstationsrch_x_Component_Type1",
                ajax: { id: "x_Component_Type1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Type1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
<?php
if (!$Page->Component_Category1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Category1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Category1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Category1"
            name="x_Component_Category1[]"
            class="form-control ew-select<?= $Page->Component_Category1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Category1"
            data-table="workstation"
            data-field="x_Component_Category1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Category1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Category1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Category1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Category1->editAttributes() ?>>
            <?= $Page->Component_Category1->selectOptionListHtml("x_Component_Category1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Category1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Category1",
                selectId: "fworkstationsrch_x_Component_Category1",
                ajax: { id: "x_Component_Category1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Category1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
<?php
if (!$Page->Component_Make1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Make1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Make1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Make1"
            name="x_Component_Make1[]"
            class="form-control ew-select<?= $Page->Component_Make1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Make1"
            data-table="workstation"
            data-field="x_Component_Make1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Make1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Make1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Make1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Make1->editAttributes() ?>>
            <?= $Page->Component_Make1->selectOptionListHtml("x_Component_Make1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Make1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Make1",
                selectId: "fworkstationsrch_x_Component_Make1",
                ajax: { id: "x_Component_Make1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Make1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
<?php
if (!$Page->Component_Model1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Model1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Model1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Model1"
            name="x_Component_Model1[]"
            class="form-control ew-select<?= $Page->Component_Model1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Model1"
            data-table="workstation"
            data-field="x_Component_Model1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Model1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Model1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Model1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Model1->editAttributes() ?>>
            <?= $Page->Component_Model1->selectOptionListHtml("x_Component_Model1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Model1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Model1",
                selectId: "fworkstationsrch_x_Component_Model1",
                ajax: { id: "x_Component_Model1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Model1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
<?php
if (!$Page->Component_Serial_Number1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Serial_Number1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Serial_Number1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Serial_Number1"
            name="x_Component_Serial_Number1[]"
            class="form-control ew-select<?= $Page->Component_Serial_Number1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Serial_Number1"
            data-table="workstation"
            data-field="x_Component_Serial_Number1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Serial_Number1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Serial_Number1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Serial_Number1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Serial_Number1->editAttributes() ?>>
            <?= $Page->Component_Serial_Number1->selectOptionListHtml("x_Component_Serial_Number1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Serial_Number1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Serial_Number1",
                selectId: "fworkstationsrch_x_Component_Serial_Number1",
                ajax: { id: "x_Component_Serial_Number1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Serial_Number1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
<?php
if (!$Page->Component_Display_Size1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Display_Size1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Display_Size1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Display_Size1"
            name="x_Component_Display_Size1[]"
            class="form-control ew-select<?= $Page->Component_Display_Size1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Display_Size1"
            data-table="workstation"
            data-field="x_Component_Display_Size1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Display_Size1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Display_Size1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Display_Size1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Display_Size1->editAttributes() ?>>
            <?= $Page->Component_Display_Size1->selectOptionListHtml("x_Component_Display_Size1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Display_Size1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Display_Size1",
                selectId: "fworkstationsrch_x_Component_Display_Size1",
                ajax: { id: "x_Component_Display_Size1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Display_Size1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
<?php
if (!$Page->Component_Keyboard_Layout1->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Keyboard_Layout1" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Keyboard_Layout1->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Keyboard_Layout1"
            name="x_Component_Keyboard_Layout1[]"
            class="form-control ew-select<?= $Page->Component_Keyboard_Layout1->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Keyboard_Layout1"
            data-table="workstation"
            data-field="x_Component_Keyboard_Layout1"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Keyboard_Layout1->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Keyboard_Layout1->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Keyboard_Layout1->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Keyboard_Layout1->editAttributes() ?>>
            <?= $Page->Component_Keyboard_Layout1->selectOptionListHtml("x_Component_Keyboard_Layout1", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Keyboard_Layout1->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Keyboard_Layout1",
                selectId: "fworkstationsrch_x_Component_Keyboard_Layout1",
                ajax: { id: "x_Component_Keyboard_Layout1", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Keyboard_Layout1.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
<?php
if (!$Page->Component_Type2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Type2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Type2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Type2"
            name="x_Component_Type2[]"
            class="form-control ew-select<?= $Page->Component_Type2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Type2"
            data-table="workstation"
            data-field="x_Component_Type2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Type2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Type2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Type2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Type2->editAttributes() ?>>
            <?= $Page->Component_Type2->selectOptionListHtml("x_Component_Type2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Type2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Type2",
                selectId: "fworkstationsrch_x_Component_Type2",
                ajax: { id: "x_Component_Type2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Type2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
<?php
if (!$Page->Component_Category2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Category2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Category2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Category2"
            name="x_Component_Category2[]"
            class="form-control ew-select<?= $Page->Component_Category2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Category2"
            data-table="workstation"
            data-field="x_Component_Category2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Category2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Category2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Category2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Category2->editAttributes() ?>>
            <?= $Page->Component_Category2->selectOptionListHtml("x_Component_Category2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Category2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Category2",
                selectId: "fworkstationsrch_x_Component_Category2",
                ajax: { id: "x_Component_Category2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Category2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
<?php
if (!$Page->Component_Make2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Make2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Make2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Make2"
            name="x_Component_Make2[]"
            class="form-control ew-select<?= $Page->Component_Make2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Make2"
            data-table="workstation"
            data-field="x_Component_Make2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Make2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Make2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Make2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Make2->editAttributes() ?>>
            <?= $Page->Component_Make2->selectOptionListHtml("x_Component_Make2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Make2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Make2",
                selectId: "fworkstationsrch_x_Component_Make2",
                ajax: { id: "x_Component_Make2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Make2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
<?php
if (!$Page->Component_Model2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Model2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Model2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Model2"
            name="x_Component_Model2[]"
            class="form-control ew-select<?= $Page->Component_Model2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Model2"
            data-table="workstation"
            data-field="x_Component_Model2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Model2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Model2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Model2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Model2->editAttributes() ?>>
            <?= $Page->Component_Model2->selectOptionListHtml("x_Component_Model2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Model2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Model2",
                selectId: "fworkstationsrch_x_Component_Model2",
                ajax: { id: "x_Component_Model2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Model2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
<?php
if (!$Page->Component_Serial_Number2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Serial_Number2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Serial_Number2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Serial_Number2"
            name="x_Component_Serial_Number2[]"
            class="form-control ew-select<?= $Page->Component_Serial_Number2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Serial_Number2"
            data-table="workstation"
            data-field="x_Component_Serial_Number2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Serial_Number2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Serial_Number2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Serial_Number2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Serial_Number2->editAttributes() ?>>
            <?= $Page->Component_Serial_Number2->selectOptionListHtml("x_Component_Serial_Number2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Serial_Number2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Serial_Number2",
                selectId: "fworkstationsrch_x_Component_Serial_Number2",
                ajax: { id: "x_Component_Serial_Number2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Serial_Number2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
<?php
if (!$Page->Component_Display_Size2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Display_Size2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Display_Size2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Display_Size2"
            name="x_Component_Display_Size2[]"
            class="form-control ew-select<?= $Page->Component_Display_Size2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Display_Size2"
            data-table="workstation"
            data-field="x_Component_Display_Size2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Display_Size2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Display_Size2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Display_Size2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Display_Size2->editAttributes() ?>>
            <?= $Page->Component_Display_Size2->selectOptionListHtml("x_Component_Display_Size2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Display_Size2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Display_Size2",
                selectId: "fworkstationsrch_x_Component_Display_Size2",
                ajax: { id: "x_Component_Display_Size2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Display_Size2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
<?php
if (!$Page->Component_Keyboard_Layout2->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Component_Keyboard_Layout2" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Component_Keyboard_Layout2->UseFilter ? " ew-filter-field" : "" ?>">
        <select
            id="x_Component_Keyboard_Layout2"
            name="x_Component_Keyboard_Layout2[]"
            class="form-control ew-select<?= $Page->Component_Keyboard_Layout2->isInvalidClass() ?>"
            data-select2-id="fworkstationsrch_x_Component_Keyboard_Layout2"
            data-table="workstation"
            data-field="x_Component_Keyboard_Layout2"
            data-caption="<?= HtmlEncode(RemoveHtml($Page->Component_Keyboard_Layout2->caption())) ?>"
            data-filter="true"
            multiple
            size="1"
            data-value-separator="<?= $Page->Component_Keyboard_Layout2->displayValueSeparatorAttribute() ?>"
            data-placeholder="<?= HtmlEncode($Page->Component_Keyboard_Layout2->getPlaceHolder()) ?>"
            data-ew-action="update-options"
            <?= $Page->Component_Keyboard_Layout2->editAttributes() ?>>
            <?= $Page->Component_Keyboard_Layout2->selectOptionListHtml("x_Component_Keyboard_Layout2", true) ?>
        </select>
        <div class="invalid-feedback"><?= $Page->Component_Keyboard_Layout2->getErrorMessage(false) ?></div>
        <script>
        loadjs.ready("fworkstationsrch", function() {
            var options = {
                name: "x_Component_Keyboard_Layout2",
                selectId: "fworkstationsrch_x_Component_Keyboard_Layout2",
                ajax: { id: "x_Component_Keyboard_Layout2", form: "fworkstationsrch", limit: ew.FILTER_PAGE_SIZE, data: { ajax: "filter" } }
            };
            options = Object.assign({}, ew.filterOptions, options, ew.vars.tables.workstation.fields.Component_Keyboard_Layout2.filterOptions);
            ew.createFilter(options);
        });
        </script>
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fworkstationsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fworkstationsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fworkstationsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fworkstationsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
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
<input type="hidden" name="t" value="workstation">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_workstation" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_workstationlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
        <th data-name="Workstation_Name" class="<?= $Page->Workstation_Name->headerCellClass() ?>"><div id="elh_workstation_Workstation_Name" class="workstation_Workstation_Name"><?= $Page->renderFieldHeader($Page->Workstation_Name) ?></div></th>
<?php } ?>
<?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
        <th data-name="Workstation_Remark" class="<?= $Page->Workstation_Remark->headerCellClass() ?>"><div id="elh_workstation_Workstation_Remark" class="workstation_Workstation_Remark"><?= $Page->renderFieldHeader($Page->Workstation_Remark) ?></div></th>
<?php } ?>
<?php if ($Page->User_Email->Visible) { // User_Email ?>
        <th data-name="User_Email" class="<?= $Page->User_Email->headerCellClass() ?>"><div id="elh_workstation_User_Email" class="workstation_User_Email"><?= $Page->renderFieldHeader($Page->User_Email) ?></div></th>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
        <th data-name="User_Name" class="<?= $Page->User_Name->headerCellClass() ?>"><div id="elh_workstation_User_Name" class="workstation_User_Name"><?= $Page->renderFieldHeader($Page->User_Name) ?></div></th>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
        <th data-name="User_Employee_Number" class="<?= $Page->User_Employee_Number->headerCellClass() ?>"><div id="elh_workstation_User_Employee_Number" class="workstation_User_Employee_Number"><?= $Page->renderFieldHeader($Page->User_Employee_Number) ?></div></th>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
        <th data-name="User_Phone_Number" class="<?= $Page->User_Phone_Number->headerCellClass() ?>"><div id="elh_workstation_User_Phone_Number" class="workstation_User_Phone_Number"><?= $Page->renderFieldHeader($Page->User_Phone_Number) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
        <th data-name="Address_Name" class="<?= $Page->Address_Name->headerCellClass() ?>"><div id="elh_workstation_Address_Name" class="workstation_Address_Name"><?= $Page->renderFieldHeader($Page->Address_Name) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
        <th data-name="Address_Street" class="<?= $Page->Address_Street->headerCellClass() ?>"><div id="elh_workstation_Address_Street" class="workstation_Address_Street"><?= $Page->renderFieldHeader($Page->Address_Street) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
        <th data-name="Address_Zipcode" class="<?= $Page->Address_Zipcode->headerCellClass() ?>"><div id="elh_workstation_Address_Zipcode" class="workstation_Address_Zipcode"><?= $Page->renderFieldHeader($Page->Address_Zipcode) ?></div></th>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
        <th data-name="Address_City" class="<?= $Page->Address_City->headerCellClass() ?>"><div id="elh_workstation_Address_City" class="workstation_Address_City"><?= $Page->renderFieldHeader($Page->Address_City) ?></div></th>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
        <th data-name="Address_Country" class="<?= $Page->Address_Country->headerCellClass() ?>"><div id="elh_workstation_Address_Country" class="workstation_Address_Country"><?= $Page->renderFieldHeader($Page->Address_Country) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Type->Visible) { // Component_Type ?>
        <th data-name="Component_Type" class="<?= $Page->Component_Type->headerCellClass() ?>"><div id="elh_workstation_Component_Type" class="workstation_Component_Type"><?= $Page->renderFieldHeader($Page->Component_Type) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Category->Visible) { // Component_Category ?>
        <th data-name="Component_Category" class="<?= $Page->Component_Category->headerCellClass() ?>"><div id="elh_workstation_Component_Category" class="workstation_Component_Category"><?= $Page->renderFieldHeader($Page->Component_Category) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Make->Visible) { // Component_Make ?>
        <th data-name="Component_Make" class="<?= $Page->Component_Make->headerCellClass() ?>"><div id="elh_workstation_Component_Make" class="workstation_Component_Make"><?= $Page->renderFieldHeader($Page->Component_Make) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Model->Visible) { // Component_Model ?>
        <th data-name="Component_Model" class="<?= $Page->Component_Model->headerCellClass() ?>"><div id="elh_workstation_Component_Model" class="workstation_Component_Model"><?= $Page->renderFieldHeader($Page->Component_Model) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
        <th data-name="Component_Serial_Number" class="<?= $Page->Component_Serial_Number->headerCellClass() ?>"><div id="elh_workstation_Component_Serial_Number" class="workstation_Component_Serial_Number"><?= $Page->renderFieldHeader($Page->Component_Serial_Number) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
        <th data-name="Component_Display_Size" class="<?= $Page->Component_Display_Size->headerCellClass() ?>"><div id="elh_workstation_Component_Display_Size" class="workstation_Component_Display_Size"><?= $Page->renderFieldHeader($Page->Component_Display_Size) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
        <th data-name="Component_Keyboard_Layout" class="<?= $Page->Component_Keyboard_Layout->headerCellClass() ?>"><div id="elh_workstation_Component_Keyboard_Layout" class="workstation_Component_Keyboard_Layout"><?= $Page->renderFieldHeader($Page->Component_Keyboard_Layout) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
        <th data-name="Component_Type1" class="<?= $Page->Component_Type1->headerCellClass() ?>"><div id="elh_workstation_Component_Type1" class="workstation_Component_Type1"><?= $Page->renderFieldHeader($Page->Component_Type1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
        <th data-name="Component_Category1" class="<?= $Page->Component_Category1->headerCellClass() ?>"><div id="elh_workstation_Component_Category1" class="workstation_Component_Category1"><?= $Page->renderFieldHeader($Page->Component_Category1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
        <th data-name="Component_Make1" class="<?= $Page->Component_Make1->headerCellClass() ?>"><div id="elh_workstation_Component_Make1" class="workstation_Component_Make1"><?= $Page->renderFieldHeader($Page->Component_Make1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
        <th data-name="Component_Model1" class="<?= $Page->Component_Model1->headerCellClass() ?>"><div id="elh_workstation_Component_Model1" class="workstation_Component_Model1"><?= $Page->renderFieldHeader($Page->Component_Model1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
        <th data-name="Component_Serial_Number1" class="<?= $Page->Component_Serial_Number1->headerCellClass() ?>"><div id="elh_workstation_Component_Serial_Number1" class="workstation_Component_Serial_Number1"><?= $Page->renderFieldHeader($Page->Component_Serial_Number1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
        <th data-name="Component_Display_Size1" class="<?= $Page->Component_Display_Size1->headerCellClass() ?>"><div id="elh_workstation_Component_Display_Size1" class="workstation_Component_Display_Size1"><?= $Page->renderFieldHeader($Page->Component_Display_Size1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
        <th data-name="Component_Keyboard_Layout1" class="<?= $Page->Component_Keyboard_Layout1->headerCellClass() ?>"><div id="elh_workstation_Component_Keyboard_Layout1" class="workstation_Component_Keyboard_Layout1"><?= $Page->renderFieldHeader($Page->Component_Keyboard_Layout1) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
        <th data-name="Component_Type2" class="<?= $Page->Component_Type2->headerCellClass() ?>"><div id="elh_workstation_Component_Type2" class="workstation_Component_Type2"><?= $Page->renderFieldHeader($Page->Component_Type2) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
        <th data-name="Component_Category2" class="<?= $Page->Component_Category2->headerCellClass() ?>"><div id="elh_workstation_Component_Category2" class="workstation_Component_Category2"><?= $Page->renderFieldHeader($Page->Component_Category2) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
        <th data-name="Component_Make2" class="<?= $Page->Component_Make2->headerCellClass() ?>"><div id="elh_workstation_Component_Make2" class="workstation_Component_Make2"><?= $Page->renderFieldHeader($Page->Component_Make2) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
        <th data-name="Component_Model2" class="<?= $Page->Component_Model2->headerCellClass() ?>"><div id="elh_workstation_Component_Model2" class="workstation_Component_Model2"><?= $Page->renderFieldHeader($Page->Component_Model2) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
        <th data-name="Component_Serial_Number2" class="<?= $Page->Component_Serial_Number2->headerCellClass() ?>"><div id="elh_workstation_Component_Serial_Number2" class="workstation_Component_Serial_Number2"><?= $Page->renderFieldHeader($Page->Component_Serial_Number2) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
        <th data-name="Component_Display_Size2" class="<?= $Page->Component_Display_Size2->headerCellClass() ?>"><div id="elh_workstation_Component_Display_Size2" class="workstation_Component_Display_Size2"><?= $Page->renderFieldHeader($Page->Component_Display_Size2) ?></div></th>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
        <th data-name="Component_Keyboard_Layout2" class="<?= $Page->Component_Keyboard_Layout2->headerCellClass() ?>"><div id="elh_workstation_Component_Keyboard_Layout2" class="workstation_Component_Keyboard_Layout2"><?= $Page->renderFieldHeader($Page->Component_Keyboard_Layout2) ?></div></th>
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
    <?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
        <td data-name="Workstation_Name"<?= $Page->Workstation_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Workstation_Name" class="el_workstation_Workstation_Name">
<span<?= $Page->Workstation_Name->viewAttributes() ?>>
<?= $Page->Workstation_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
        <td data-name="Workstation_Remark"<?= $Page->Workstation_Remark->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Workstation_Remark" class="el_workstation_Workstation_Remark">
<span<?= $Page->Workstation_Remark->viewAttributes() ?>>
<?= $Page->Workstation_Remark->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Email->Visible) { // User_Email ?>
        <td data-name="User_Email"<?= $Page->User_Email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Email" class="el_workstation_User_Email">
<span<?= $Page->User_Email->viewAttributes() ?>>
<?= $Page->User_Email->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Name->Visible) { // User_Name ?>
        <td data-name="User_Name"<?= $Page->User_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Name" class="el_workstation_User_Name">
<span<?= $Page->User_Name->viewAttributes() ?>>
<?= $Page->User_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
        <td data-name="User_Employee_Number"<?= $Page->User_Employee_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Employee_Number" class="el_workstation_User_Employee_Number">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<?= $Page->User_Employee_Number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
        <td data-name="User_Phone_Number"<?= $Page->User_Phone_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Phone_Number" class="el_workstation_User_Phone_Number">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<?= $Page->User_Phone_Number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Name->Visible) { // Address_Name ?>
        <td data-name="Address_Name"<?= $Page->Address_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Name" class="el_workstation_Address_Name">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<?= $Page->Address_Name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Street->Visible) { // Address_Street ?>
        <td data-name="Address_Street"<?= $Page->Address_Street->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Street" class="el_workstation_Address_Street">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<?= $Page->Address_Street->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
        <td data-name="Address_Zipcode"<?= $Page->Address_Zipcode->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Zipcode" class="el_workstation_Address_Zipcode">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<?= $Page->Address_Zipcode->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_City->Visible) { // Address_City ?>
        <td data-name="Address_City"<?= $Page->Address_City->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_City" class="el_workstation_Address_City">
<span<?= $Page->Address_City->viewAttributes() ?>>
<?= $Page->Address_City->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Address_Country->Visible) { // Address_Country ?>
        <td data-name="Address_Country"<?= $Page->Address_Country->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Country" class="el_workstation_Address_Country">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<?= $Page->Address_Country->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Type->Visible) { // Component_Type ?>
        <td data-name="Component_Type"<?= $Page->Component_Type->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Type" class="el_workstation_Component_Type">
<span<?= $Page->Component_Type->viewAttributes() ?>>
<?= $Page->Component_Type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Category->Visible) { // Component_Category ?>
        <td data-name="Component_Category"<?= $Page->Component_Category->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Category" class="el_workstation_Component_Category">
<span<?= $Page->Component_Category->viewAttributes() ?>>
<?= $Page->Component_Category->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Make->Visible) { // Component_Make ?>
        <td data-name="Component_Make"<?= $Page->Component_Make->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Make" class="el_workstation_Component_Make">
<span<?= $Page->Component_Make->viewAttributes() ?>>
<?= $Page->Component_Make->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Model->Visible) { // Component_Model ?>
        <td data-name="Component_Model"<?= $Page->Component_Model->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Model" class="el_workstation_Component_Model">
<span<?= $Page->Component_Model->viewAttributes() ?>>
<?= $Page->Component_Model->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
        <td data-name="Component_Serial_Number"<?= $Page->Component_Serial_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Serial_Number" class="el_workstation_Component_Serial_Number">
<span<?= $Page->Component_Serial_Number->viewAttributes() ?>>
<?= $Page->Component_Serial_Number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
        <td data-name="Component_Display_Size"<?= $Page->Component_Display_Size->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Display_Size" class="el_workstation_Component_Display_Size">
<span<?= $Page->Component_Display_Size->viewAttributes() ?>>
<?= $Page->Component_Display_Size->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
        <td data-name="Component_Keyboard_Layout"<?= $Page->Component_Keyboard_Layout->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Keyboard_Layout" class="el_workstation_Component_Keyboard_Layout">
<span<?= $Page->Component_Keyboard_Layout->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
        <td data-name="Component_Type1"<?= $Page->Component_Type1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Type1" class="el_workstation_Component_Type1">
<span<?= $Page->Component_Type1->viewAttributes() ?>>
<?= $Page->Component_Type1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
        <td data-name="Component_Category1"<?= $Page->Component_Category1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Category1" class="el_workstation_Component_Category1">
<span<?= $Page->Component_Category1->viewAttributes() ?>>
<?= $Page->Component_Category1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
        <td data-name="Component_Make1"<?= $Page->Component_Make1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Make1" class="el_workstation_Component_Make1">
<span<?= $Page->Component_Make1->viewAttributes() ?>>
<?= $Page->Component_Make1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
        <td data-name="Component_Model1"<?= $Page->Component_Model1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Model1" class="el_workstation_Component_Model1">
<span<?= $Page->Component_Model1->viewAttributes() ?>>
<?= $Page->Component_Model1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
        <td data-name="Component_Serial_Number1"<?= $Page->Component_Serial_Number1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Serial_Number1" class="el_workstation_Component_Serial_Number1">
<span<?= $Page->Component_Serial_Number1->viewAttributes() ?>>
<?= $Page->Component_Serial_Number1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
        <td data-name="Component_Display_Size1"<?= $Page->Component_Display_Size1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Display_Size1" class="el_workstation_Component_Display_Size1">
<span<?= $Page->Component_Display_Size1->viewAttributes() ?>>
<?= $Page->Component_Display_Size1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
        <td data-name="Component_Keyboard_Layout1"<?= $Page->Component_Keyboard_Layout1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Keyboard_Layout1" class="el_workstation_Component_Keyboard_Layout1">
<span<?= $Page->Component_Keyboard_Layout1->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
        <td data-name="Component_Type2"<?= $Page->Component_Type2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Type2" class="el_workstation_Component_Type2">
<span<?= $Page->Component_Type2->viewAttributes() ?>>
<?= $Page->Component_Type2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
        <td data-name="Component_Category2"<?= $Page->Component_Category2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Category2" class="el_workstation_Component_Category2">
<span<?= $Page->Component_Category2->viewAttributes() ?>>
<?= $Page->Component_Category2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
        <td data-name="Component_Make2"<?= $Page->Component_Make2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Make2" class="el_workstation_Component_Make2">
<span<?= $Page->Component_Make2->viewAttributes() ?>>
<?= $Page->Component_Make2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
        <td data-name="Component_Model2"<?= $Page->Component_Model2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Model2" class="el_workstation_Component_Model2">
<span<?= $Page->Component_Model2->viewAttributes() ?>>
<?= $Page->Component_Model2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
        <td data-name="Component_Serial_Number2"<?= $Page->Component_Serial_Number2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Serial_Number2" class="el_workstation_Component_Serial_Number2">
<span<?= $Page->Component_Serial_Number2->viewAttributes() ?>>
<?= $Page->Component_Serial_Number2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
        <td data-name="Component_Display_Size2"<?= $Page->Component_Display_Size2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Display_Size2" class="el_workstation_Component_Display_Size2">
<span<?= $Page->Component_Display_Size2->viewAttributes() ?>>
<?= $Page->Component_Display_Size2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
        <td data-name="Component_Keyboard_Layout2"<?= $Page->Component_Keyboard_Layout2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Keyboard_Layout2" class="el_workstation_Component_Keyboard_Layout2">
<span<?= $Page->Component_Keyboard_Layout2->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout2->getViewValue() ?></span>
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
    ew.addEventHandlers("workstation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
