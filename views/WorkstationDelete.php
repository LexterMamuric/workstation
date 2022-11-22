<?php

namespace WorkStationDB\project3;

// Page object
$WorkstationDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { workstation: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fworkstationdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fworkstationdelete")
        .setPageId("delete")
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
<form name="fworkstationdelete" id="fworkstationdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="workstation">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
        <th class="<?= $Page->Workstation_Name->headerCellClass() ?>"><span id="elh_workstation_Workstation_Name" class="workstation_Workstation_Name"><?= $Page->Workstation_Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
        <th class="<?= $Page->Workstation_Remark->headerCellClass() ?>"><span id="elh_workstation_Workstation_Remark" class="workstation_Workstation_Remark"><?= $Page->Workstation_Remark->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User_Email->Visible) { // User_Email ?>
        <th class="<?= $Page->User_Email->headerCellClass() ?>"><span id="elh_workstation_User_Email" class="workstation_User_Email"><?= $Page->User_Email->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
        <th class="<?= $Page->User_Name->headerCellClass() ?>"><span id="elh_workstation_User_Name" class="workstation_User_Name"><?= $Page->User_Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
        <th class="<?= $Page->User_Employee_Number->headerCellClass() ?>"><span id="elh_workstation_User_Employee_Number" class="workstation_User_Employee_Number"><?= $Page->User_Employee_Number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
        <th class="<?= $Page->User_Phone_Number->headerCellClass() ?>"><span id="elh_workstation_User_Phone_Number" class="workstation_User_Phone_Number"><?= $Page->User_Phone_Number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
        <th class="<?= $Page->Address_Name->headerCellClass() ?>"><span id="elh_workstation_Address_Name" class="workstation_Address_Name"><?= $Page->Address_Name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
        <th class="<?= $Page->Address_Street->headerCellClass() ?>"><span id="elh_workstation_Address_Street" class="workstation_Address_Street"><?= $Page->Address_Street->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
        <th class="<?= $Page->Address_Zipcode->headerCellClass() ?>"><span id="elh_workstation_Address_Zipcode" class="workstation_Address_Zipcode"><?= $Page->Address_Zipcode->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
        <th class="<?= $Page->Address_City->headerCellClass() ?>"><span id="elh_workstation_Address_City" class="workstation_Address_City"><?= $Page->Address_City->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
        <th class="<?= $Page->Address_Country->headerCellClass() ?>"><span id="elh_workstation_Address_Country" class="workstation_Address_Country"><?= $Page->Address_Country->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Type->Visible) { // Component_Type ?>
        <th class="<?= $Page->Component_Type->headerCellClass() ?>"><span id="elh_workstation_Component_Type" class="workstation_Component_Type"><?= $Page->Component_Type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Category->Visible) { // Component_Category ?>
        <th class="<?= $Page->Component_Category->headerCellClass() ?>"><span id="elh_workstation_Component_Category" class="workstation_Component_Category"><?= $Page->Component_Category->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Make->Visible) { // Component_Make ?>
        <th class="<?= $Page->Component_Make->headerCellClass() ?>"><span id="elh_workstation_Component_Make" class="workstation_Component_Make"><?= $Page->Component_Make->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Model->Visible) { // Component_Model ?>
        <th class="<?= $Page->Component_Model->headerCellClass() ?>"><span id="elh_workstation_Component_Model" class="workstation_Component_Model"><?= $Page->Component_Model->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
        <th class="<?= $Page->Component_Serial_Number->headerCellClass() ?>"><span id="elh_workstation_Component_Serial_Number" class="workstation_Component_Serial_Number"><?= $Page->Component_Serial_Number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
        <th class="<?= $Page->Component_Display_Size->headerCellClass() ?>"><span id="elh_workstation_Component_Display_Size" class="workstation_Component_Display_Size"><?= $Page->Component_Display_Size->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
        <th class="<?= $Page->Component_Keyboard_Layout->headerCellClass() ?>"><span id="elh_workstation_Component_Keyboard_Layout" class="workstation_Component_Keyboard_Layout"><?= $Page->Component_Keyboard_Layout->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
        <th class="<?= $Page->Component_Type1->headerCellClass() ?>"><span id="elh_workstation_Component_Type1" class="workstation_Component_Type1"><?= $Page->Component_Type1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
        <th class="<?= $Page->Component_Category1->headerCellClass() ?>"><span id="elh_workstation_Component_Category1" class="workstation_Component_Category1"><?= $Page->Component_Category1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
        <th class="<?= $Page->Component_Make1->headerCellClass() ?>"><span id="elh_workstation_Component_Make1" class="workstation_Component_Make1"><?= $Page->Component_Make1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
        <th class="<?= $Page->Component_Model1->headerCellClass() ?>"><span id="elh_workstation_Component_Model1" class="workstation_Component_Model1"><?= $Page->Component_Model1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
        <th class="<?= $Page->Component_Serial_Number1->headerCellClass() ?>"><span id="elh_workstation_Component_Serial_Number1" class="workstation_Component_Serial_Number1"><?= $Page->Component_Serial_Number1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
        <th class="<?= $Page->Component_Display_Size1->headerCellClass() ?>"><span id="elh_workstation_Component_Display_Size1" class="workstation_Component_Display_Size1"><?= $Page->Component_Display_Size1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
        <th class="<?= $Page->Component_Keyboard_Layout1->headerCellClass() ?>"><span id="elh_workstation_Component_Keyboard_Layout1" class="workstation_Component_Keyboard_Layout1"><?= $Page->Component_Keyboard_Layout1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
        <th class="<?= $Page->Component_Type2->headerCellClass() ?>"><span id="elh_workstation_Component_Type2" class="workstation_Component_Type2"><?= $Page->Component_Type2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
        <th class="<?= $Page->Component_Category2->headerCellClass() ?>"><span id="elh_workstation_Component_Category2" class="workstation_Component_Category2"><?= $Page->Component_Category2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
        <th class="<?= $Page->Component_Make2->headerCellClass() ?>"><span id="elh_workstation_Component_Make2" class="workstation_Component_Make2"><?= $Page->Component_Make2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
        <th class="<?= $Page->Component_Model2->headerCellClass() ?>"><span id="elh_workstation_Component_Model2" class="workstation_Component_Model2"><?= $Page->Component_Model2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
        <th class="<?= $Page->Component_Serial_Number2->headerCellClass() ?>"><span id="elh_workstation_Component_Serial_Number2" class="workstation_Component_Serial_Number2"><?= $Page->Component_Serial_Number2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
        <th class="<?= $Page->Component_Display_Size2->headerCellClass() ?>"><span id="elh_workstation_Component_Display_Size2" class="workstation_Component_Display_Size2"><?= $Page->Component_Display_Size2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
        <th class="<?= $Page->Component_Keyboard_Layout2->headerCellClass() ?>"><span id="elh_workstation_Component_Keyboard_Layout2" class="workstation_Component_Keyboard_Layout2"><?= $Page->Component_Keyboard_Layout2->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->Workstation_Name->Visible) { // Workstation_Name ?>
        <td<?= $Page->Workstation_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Workstation_Name" class="el_workstation_Workstation_Name">
<span<?= $Page->Workstation_Name->viewAttributes() ?>>
<?= $Page->Workstation_Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Workstation_Remark->Visible) { // Workstation_Remark ?>
        <td<?= $Page->Workstation_Remark->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Workstation_Remark" class="el_workstation_Workstation_Remark">
<span<?= $Page->Workstation_Remark->viewAttributes() ?>>
<?= $Page->Workstation_Remark->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User_Email->Visible) { // User_Email ?>
        <td<?= $Page->User_Email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Email" class="el_workstation_User_Email">
<span<?= $Page->User_Email->viewAttributes() ?>>
<?= $Page->User_Email->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User_Name->Visible) { // User_Name ?>
        <td<?= $Page->User_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Name" class="el_workstation_User_Name">
<span<?= $Page->User_Name->viewAttributes() ?>>
<?= $Page->User_Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User_Employee_Number->Visible) { // User_Employee_Number ?>
        <td<?= $Page->User_Employee_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Employee_Number" class="el_workstation_User_Employee_Number">
<span<?= $Page->User_Employee_Number->viewAttributes() ?>>
<?= $Page->User_Employee_Number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->User_Phone_Number->Visible) { // User_Phone_Number ?>
        <td<?= $Page->User_Phone_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_User_Phone_Number" class="el_workstation_User_Phone_Number">
<span<?= $Page->User_Phone_Number->viewAttributes() ?>>
<?= $Page->User_Phone_Number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Address_Name->Visible) { // Address_Name ?>
        <td<?= $Page->Address_Name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Name" class="el_workstation_Address_Name">
<span<?= $Page->Address_Name->viewAttributes() ?>>
<?= $Page->Address_Name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Address_Street->Visible) { // Address_Street ?>
        <td<?= $Page->Address_Street->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Street" class="el_workstation_Address_Street">
<span<?= $Page->Address_Street->viewAttributes() ?>>
<?= $Page->Address_Street->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Address_Zipcode->Visible) { // Address_Zipcode ?>
        <td<?= $Page->Address_Zipcode->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Zipcode" class="el_workstation_Address_Zipcode">
<span<?= $Page->Address_Zipcode->viewAttributes() ?>>
<?= $Page->Address_Zipcode->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Address_City->Visible) { // Address_City ?>
        <td<?= $Page->Address_City->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_City" class="el_workstation_Address_City">
<span<?= $Page->Address_City->viewAttributes() ?>>
<?= $Page->Address_City->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Address_Country->Visible) { // Address_Country ?>
        <td<?= $Page->Address_Country->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Address_Country" class="el_workstation_Address_Country">
<span<?= $Page->Address_Country->viewAttributes() ?>>
<?= $Page->Address_Country->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Type->Visible) { // Component_Type ?>
        <td<?= $Page->Component_Type->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Type" class="el_workstation_Component_Type">
<span<?= $Page->Component_Type->viewAttributes() ?>>
<?= $Page->Component_Type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Category->Visible) { // Component_Category ?>
        <td<?= $Page->Component_Category->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Category" class="el_workstation_Component_Category">
<span<?= $Page->Component_Category->viewAttributes() ?>>
<?= $Page->Component_Category->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Make->Visible) { // Component_Make ?>
        <td<?= $Page->Component_Make->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Make" class="el_workstation_Component_Make">
<span<?= $Page->Component_Make->viewAttributes() ?>>
<?= $Page->Component_Make->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Model->Visible) { // Component_Model ?>
        <td<?= $Page->Component_Model->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Model" class="el_workstation_Component_Model">
<span<?= $Page->Component_Model->viewAttributes() ?>>
<?= $Page->Component_Model->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Serial_Number->Visible) { // Component_Serial_Number ?>
        <td<?= $Page->Component_Serial_Number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Serial_Number" class="el_workstation_Component_Serial_Number">
<span<?= $Page->Component_Serial_Number->viewAttributes() ?>>
<?= $Page->Component_Serial_Number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Display_Size->Visible) { // Component_Display_Size ?>
        <td<?= $Page->Component_Display_Size->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Display_Size" class="el_workstation_Component_Display_Size">
<span<?= $Page->Component_Display_Size->viewAttributes() ?>>
<?= $Page->Component_Display_Size->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout->Visible) { // Component_Keyboard_Layout ?>
        <td<?= $Page->Component_Keyboard_Layout->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Keyboard_Layout" class="el_workstation_Component_Keyboard_Layout">
<span<?= $Page->Component_Keyboard_Layout->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Type1->Visible) { // Component_Type1 ?>
        <td<?= $Page->Component_Type1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Type1" class="el_workstation_Component_Type1">
<span<?= $Page->Component_Type1->viewAttributes() ?>>
<?= $Page->Component_Type1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Category1->Visible) { // Component_Category1 ?>
        <td<?= $Page->Component_Category1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Category1" class="el_workstation_Component_Category1">
<span<?= $Page->Component_Category1->viewAttributes() ?>>
<?= $Page->Component_Category1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Make1->Visible) { // Component_Make1 ?>
        <td<?= $Page->Component_Make1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Make1" class="el_workstation_Component_Make1">
<span<?= $Page->Component_Make1->viewAttributes() ?>>
<?= $Page->Component_Make1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Model1->Visible) { // Component_Model1 ?>
        <td<?= $Page->Component_Model1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Model1" class="el_workstation_Component_Model1">
<span<?= $Page->Component_Model1->viewAttributes() ?>>
<?= $Page->Component_Model1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Serial_Number1->Visible) { // Component_Serial_Number1 ?>
        <td<?= $Page->Component_Serial_Number1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Serial_Number1" class="el_workstation_Component_Serial_Number1">
<span<?= $Page->Component_Serial_Number1->viewAttributes() ?>>
<?= $Page->Component_Serial_Number1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Display_Size1->Visible) { // Component_Display_Size1 ?>
        <td<?= $Page->Component_Display_Size1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Display_Size1" class="el_workstation_Component_Display_Size1">
<span<?= $Page->Component_Display_Size1->viewAttributes() ?>>
<?= $Page->Component_Display_Size1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout1->Visible) { // Component_Keyboard_Layout1 ?>
        <td<?= $Page->Component_Keyboard_Layout1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Keyboard_Layout1" class="el_workstation_Component_Keyboard_Layout1">
<span<?= $Page->Component_Keyboard_Layout1->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Type2->Visible) { // Component_Type2 ?>
        <td<?= $Page->Component_Type2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Type2" class="el_workstation_Component_Type2">
<span<?= $Page->Component_Type2->viewAttributes() ?>>
<?= $Page->Component_Type2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Category2->Visible) { // Component_Category2 ?>
        <td<?= $Page->Component_Category2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Category2" class="el_workstation_Component_Category2">
<span<?= $Page->Component_Category2->viewAttributes() ?>>
<?= $Page->Component_Category2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Make2->Visible) { // Component_Make2 ?>
        <td<?= $Page->Component_Make2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Make2" class="el_workstation_Component_Make2">
<span<?= $Page->Component_Make2->viewAttributes() ?>>
<?= $Page->Component_Make2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Model2->Visible) { // Component_Model2 ?>
        <td<?= $Page->Component_Model2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Model2" class="el_workstation_Component_Model2">
<span<?= $Page->Component_Model2->viewAttributes() ?>>
<?= $Page->Component_Model2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Serial_Number2->Visible) { // Component_Serial_Number2 ?>
        <td<?= $Page->Component_Serial_Number2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Serial_Number2" class="el_workstation_Component_Serial_Number2">
<span<?= $Page->Component_Serial_Number2->viewAttributes() ?>>
<?= $Page->Component_Serial_Number2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Display_Size2->Visible) { // Component_Display_Size2 ?>
        <td<?= $Page->Component_Display_Size2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Display_Size2" class="el_workstation_Component_Display_Size2">
<span<?= $Page->Component_Display_Size2->viewAttributes() ?>>
<?= $Page->Component_Display_Size2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Component_Keyboard_Layout2->Visible) { // Component_Keyboard_Layout2 ?>
        <td<?= $Page->Component_Keyboard_Layout2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_workstation_Component_Keyboard_Layout2" class="el_workstation_Component_Keyboard_Layout2">
<span<?= $Page->Component_Keyboard_Layout2->viewAttributes() ?>>
<?= $Page->Component_Keyboard_Layout2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
