<?php

namespace WorkStationDB\project3;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class WorkstationDelete extends Workstation
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "WorkstationDelete";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "workstationdelete";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer;
        $this->TableVar = 'workstation';
        $this->TableName = 'workstation';

        // Table CSS class
        $this->TableClass = "table table-bordered table-hover table-sm ew-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (workstation)
        if (!isset($GLOBALS["workstation"]) || get_class($GLOBALS["workstation"]) == PROJECT_NAMESPACE . "workstation") {
            $GLOBALS["workstation"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'workstation');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->id->Visible = false;
        }
    }
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $TotalRecords = 0;
    public $RecordCount;
    public $RecKeys = [];
    public $StartRowCount = 1;
    public $RowCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));
        $this->CurrentAction = Param("action"); // Set up current action
        $this->id->Visible = false;
        $this->Workstation_Name->setVisibility();
        $this->Workstation_Remark->setVisibility();
        $this->User_Email->setVisibility();
        $this->User_Name->setVisibility();
        $this->User_Employee_Number->setVisibility();
        $this->User_Phone_Number->setVisibility();
        $this->Address_Name->setVisibility();
        $this->Address_Street->setVisibility();
        $this->Address_Zipcode->setVisibility();
        $this->Address_City->setVisibility();
        $this->Address_Country->setVisibility();
        $this->Component_Type->setVisibility();
        $this->Component_Category->setVisibility();
        $this->Component_Make->setVisibility();
        $this->Component_Model->setVisibility();
        $this->Component_Serial_Number->setVisibility();
        $this->Component_Display_Size->setVisibility();
        $this->Component_Keyboard_Layout->setVisibility();
        $this->Component_Type1->setVisibility();
        $this->Component_Category1->setVisibility();
        $this->Component_Make1->setVisibility();
        $this->Component_Model1->setVisibility();
        $this->Component_Serial_Number1->setVisibility();
        $this->Component_Display_Size1->setVisibility();
        $this->Component_Keyboard_Layout1->setVisibility();
        $this->Component_Type2->setVisibility();
        $this->Component_Category2->setVisibility();
        $this->Component_Make2->setVisibility();
        $this->Component_Model2->setVisibility();
        $this->Component_Serial_Number2->setVisibility();
        $this->Component_Display_Size2->setVisibility();
        $this->Component_Keyboard_Layout2->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->Component_Type);
        $this->setupLookupOptions($this->Component_Category);
        $this->setupLookupOptions($this->Component_Make);
        $this->setupLookupOptions($this->Component_Model);
        $this->setupLookupOptions($this->Component_Type1);
        $this->setupLookupOptions($this->Component_Display_Size1);
        $this->setupLookupOptions($this->Component_Keyboard_Layout1);
        $this->setupLookupOptions($this->Component_Type2);
        $this->setupLookupOptions($this->Component_Category2);
        $this->setupLookupOptions($this->Component_Make2);
        $this->setupLookupOptions($this->Component_Model2);
        $this->setupLookupOptions($this->Component_Display_Size2);
        $this->setupLookupOptions($this->Component_Keyboard_Layout2);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("workstationlist"); // Prevent SQL injection, return to list
            return;
        }

        // Set up filter (WHERE Clause)
        $this->CurrentFilter = $filter;

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Param("action") !== null) {
            $this->CurrentAction = Param("action") == "delete" ? "delete" : "show";
        } else {
            $this->CurrentAction = $this->InlineDelete ?
                "delete" : // Delete record directly
                "show"; // Display record
        }
        if ($this->isDelete()) {
            $this->SendEmail = true; // Send email on delete success
            if ($this->deleteRows()) { // Delete rows
                if ($this->getSuccessMessage() == "") {
                    $this->setSuccessMessage($Language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsJsonResponse()) {
                    $this->terminate(true);
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsJsonResponse()) {
                    $this->terminate();
                    return;
                }
                // Return JSON error message if UseAjaxActions
                if ($this->UseAjaxActions) {
                    WriteJson([ "success" => false, "error" => $this->getFailureMessage() ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;                    
                }
                if ($this->InlineDelete) {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                } else {
                    $this->CurrentAction = "show"; // Display record
                }
            }
        }
        if ($this->isShow()) { // Load records for display
            if ($this->Recordset = $this->loadRecordset()) {
                $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
            }
            if ($this->TotalRecords <= 0) { // No record found, exit
                if ($this->Recordset) {
                    $this->Recordset->close();
                }
                $this->terminate("workstationlist"); // Return to list
                return;
            }
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        $rs = new Recordset($result, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    // Load records as associative array
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        return $result->fetchAllAssociative();
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
        $this->id->setDbValue($row['id']);
        $this->Workstation_Name->setDbValue($row['Workstation_Name']);
        $this->Workstation_Remark->setDbValue($row['Workstation_Remark']);
        $this->User_Email->setDbValue($row['User_Email']);
        $this->User_Name->setDbValue($row['User_Name']);
        $this->User_Employee_Number->setDbValue($row['User_Employee_Number']);
        $this->User_Phone_Number->setDbValue($row['User_Phone_Number']);
        $this->Address_Name->setDbValue($row['Address_Name']);
        $this->Address_Street->setDbValue($row['Address_Street']);
        $this->Address_Zipcode->setDbValue($row['Address_Zipcode']);
        $this->Address_City->setDbValue($row['Address_City']);
        $this->Address_Country->setDbValue($row['Address_Country']);
        $this->Component_Type->setDbValue($row['Component_Type']);
        $this->Component_Category->setDbValue($row['Component_Category']);
        $this->Component_Make->setDbValue($row['Component_Make']);
        $this->Component_Model->setDbValue($row['Component_Model']);
        $this->Component_Serial_Number->setDbValue($row['Component_Serial_Number']);
        $this->Component_Display_Size->setDbValue($row['Component_Display_Size']);
        $this->Component_Keyboard_Layout->setDbValue($row['Component_Keyboard_Layout']);
        $this->Component_Type1->setDbValue($row['Component_Type1']);
        $this->Component_Category1->setDbValue($row['Component_Category1']);
        $this->Component_Make1->setDbValue($row['Component_Make1']);
        $this->Component_Model1->setDbValue($row['Component_Model1']);
        $this->Component_Serial_Number1->setDbValue($row['Component_Serial_Number1']);
        $this->Component_Display_Size1->setDbValue($row['Component_Display_Size1']);
        $this->Component_Keyboard_Layout1->setDbValue($row['Component_Keyboard_Layout1']);
        $this->Component_Type2->setDbValue($row['Component_Type2']);
        $this->Component_Category2->setDbValue($row['Component_Category2']);
        $this->Component_Make2->setDbValue($row['Component_Make2']);
        $this->Component_Model2->setDbValue($row['Component_Model2']);
        $this->Component_Serial_Number2->setDbValue($row['Component_Serial_Number2']);
        $this->Component_Display_Size2->setDbValue($row['Component_Display_Size2']);
        $this->Component_Keyboard_Layout2->setDbValue($row['Component_Keyboard_Layout2']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['Workstation_Name'] = $this->Workstation_Name->DefaultValue;
        $row['Workstation_Remark'] = $this->Workstation_Remark->DefaultValue;
        $row['User_Email'] = $this->User_Email->DefaultValue;
        $row['User_Name'] = $this->User_Name->DefaultValue;
        $row['User_Employee_Number'] = $this->User_Employee_Number->DefaultValue;
        $row['User_Phone_Number'] = $this->User_Phone_Number->DefaultValue;
        $row['Address_Name'] = $this->Address_Name->DefaultValue;
        $row['Address_Street'] = $this->Address_Street->DefaultValue;
        $row['Address_Zipcode'] = $this->Address_Zipcode->DefaultValue;
        $row['Address_City'] = $this->Address_City->DefaultValue;
        $row['Address_Country'] = $this->Address_Country->DefaultValue;
        $row['Component_Type'] = $this->Component_Type->DefaultValue;
        $row['Component_Category'] = $this->Component_Category->DefaultValue;
        $row['Component_Make'] = $this->Component_Make->DefaultValue;
        $row['Component_Model'] = $this->Component_Model->DefaultValue;
        $row['Component_Serial_Number'] = $this->Component_Serial_Number->DefaultValue;
        $row['Component_Display_Size'] = $this->Component_Display_Size->DefaultValue;
        $row['Component_Keyboard_Layout'] = $this->Component_Keyboard_Layout->DefaultValue;
        $row['Component_Type1'] = $this->Component_Type1->DefaultValue;
        $row['Component_Category1'] = $this->Component_Category1->DefaultValue;
        $row['Component_Make1'] = $this->Component_Make1->DefaultValue;
        $row['Component_Model1'] = $this->Component_Model1->DefaultValue;
        $row['Component_Serial_Number1'] = $this->Component_Serial_Number1->DefaultValue;
        $row['Component_Display_Size1'] = $this->Component_Display_Size1->DefaultValue;
        $row['Component_Keyboard_Layout1'] = $this->Component_Keyboard_Layout1->DefaultValue;
        $row['Component_Type2'] = $this->Component_Type2->DefaultValue;
        $row['Component_Category2'] = $this->Component_Category2->DefaultValue;
        $row['Component_Make2'] = $this->Component_Make2->DefaultValue;
        $row['Component_Model2'] = $this->Component_Model2->DefaultValue;
        $row['Component_Serial_Number2'] = $this->Component_Serial_Number2->DefaultValue;
        $row['Component_Display_Size2'] = $this->Component_Display_Size2->DefaultValue;
        $row['Component_Keyboard_Layout2'] = $this->Component_Keyboard_Layout2->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id
        $this->id->CellCssStyle = "white-space: nowrap;";

        // Workstation_Name

        // Workstation_Remark

        // User_Email

        // User_Name

        // User_Employee_Number

        // User_Phone_Number

        // Address_Name

        // Address_Street

        // Address_Zipcode

        // Address_City

        // Address_Country

        // Component_Type

        // Component_Category

        // Component_Make

        // Component_Model

        // Component_Serial_Number

        // Component_Display_Size

        // Component_Keyboard_Layout

        // Component_Type1

        // Component_Category1

        // Component_Make1

        // Component_Model1

        // Component_Serial_Number1

        // Component_Display_Size1

        // Component_Keyboard_Layout1

        // Component_Type2

        // Component_Category2

        // Component_Make2

        // Component_Model2

        // Component_Serial_Number2

        // Component_Display_Size2

        // Component_Keyboard_Layout2

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // Workstation_Name
            $this->Workstation_Name->ViewValue = $this->Workstation_Name->CurrentValue;

            // Workstation_Remark
            $this->Workstation_Remark->ViewValue = $this->Workstation_Remark->CurrentValue;

            // User_Email
            $this->User_Email->ViewValue = $this->User_Email->CurrentValue;

            // User_Name
            $this->User_Name->ViewValue = $this->User_Name->CurrentValue;

            // User_Employee_Number
            $this->User_Employee_Number->ViewValue = $this->User_Employee_Number->CurrentValue;

            // User_Phone_Number
            $this->User_Phone_Number->ViewValue = $this->User_Phone_Number->CurrentValue;

            // Address_Name
            $this->Address_Name->ViewValue = $this->Address_Name->CurrentValue;

            // Address_Street
            $this->Address_Street->ViewValue = $this->Address_Street->CurrentValue;

            // Address_Zipcode
            $this->Address_Zipcode->ViewValue = $this->Address_Zipcode->CurrentValue;

            // Address_City
            $this->Address_City->ViewValue = $this->Address_City->CurrentValue;

            // Address_Country
            $this->Address_Country->ViewValue = $this->Address_Country->CurrentValue;

            // Component_Type
            $curVal = strval($this->Component_Type->CurrentValue);
            if ($curVal != "") {
                $this->Component_Type->ViewValue = $this->Component_Type->lookupCacheOption($curVal);
                if ($this->Component_Type->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Type`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Type->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Type->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Type->ViewValue = $this->Component_Type->displayValue($arwrk);
                    } else {
                        $this->Component_Type->ViewValue = $this->Component_Type->CurrentValue;
                    }
                }
            } else {
                $this->Component_Type->ViewValue = null;
            }

            // Component_Category
            $curVal = strval($this->Component_Category->CurrentValue);
            if ($curVal != "") {
                $this->Component_Category->ViewValue = $this->Component_Category->lookupCacheOption($curVal);
                if ($this->Component_Category->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Category`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Category->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Category->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Category->ViewValue = $this->Component_Category->displayValue($arwrk);
                    } else {
                        $this->Component_Category->ViewValue = $this->Component_Category->CurrentValue;
                    }
                }
            } else {
                $this->Component_Category->ViewValue = null;
            }

            // Component_Make
            $curVal = strval($this->Component_Make->CurrentValue);
            if ($curVal != "") {
                $this->Component_Make->ViewValue = $this->Component_Make->lookupCacheOption($curVal);
                if ($this->Component_Make->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Make`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Make->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Make->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Make->ViewValue = $this->Component_Make->displayValue($arwrk);
                    } else {
                        $this->Component_Make->ViewValue = $this->Component_Make->CurrentValue;
                    }
                }
            } else {
                $this->Component_Make->ViewValue = null;
            }

            // Component_Model
            $curVal = strval($this->Component_Model->CurrentValue);
            if ($curVal != "") {
                $this->Component_Model->ViewValue = $this->Component_Model->lookupCacheOption($curVal);
                if ($this->Component_Model->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Model`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Model->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Model->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Model->ViewValue = $this->Component_Model->displayValue($arwrk);
                    } else {
                        $this->Component_Model->ViewValue = $this->Component_Model->CurrentValue;
                    }
                }
            } else {
                $this->Component_Model->ViewValue = null;
            }

            // Component_Serial_Number
            $this->Component_Serial_Number->ViewValue = $this->Component_Serial_Number->CurrentValue;

            // Component_Display_Size
            $this->Component_Display_Size->ViewValue = $this->Component_Display_Size->CurrentValue;

            // Component_Keyboard_Layout
            $this->Component_Keyboard_Layout->ViewValue = $this->Component_Keyboard_Layout->CurrentValue;

            // Component_Type1
            $curVal = strval($this->Component_Type1->CurrentValue);
            if ($curVal != "") {
                $this->Component_Type1->ViewValue = $this->Component_Type1->lookupCacheOption($curVal);
                if ($this->Component_Type1->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Type`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Type1->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Type1->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Type1->ViewValue = $this->Component_Type1->displayValue($arwrk);
                    } else {
                        $this->Component_Type1->ViewValue = $this->Component_Type1->CurrentValue;
                    }
                }
            } else {
                $this->Component_Type1->ViewValue = null;
            }

            // Component_Category1

            // Component_Make1

            // Component_Model1
            $this->Component_Model1->ViewValue = $this->Component_Model1->CurrentValue;

            // Component_Serial_Number1
            $this->Component_Serial_Number1->ViewValue = $this->Component_Serial_Number1->CurrentValue;

            // Component_Display_Size1
            $curVal = strval($this->Component_Display_Size1->CurrentValue);
            if ($curVal != "") {
                $this->Component_Display_Size1->ViewValue = $this->Component_Display_Size1->lookupCacheOption($curVal);
                if ($this->Component_Display_Size1->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Display Size`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Display_Size1->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Display_Size1->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Display_Size1->ViewValue = $this->Component_Display_Size1->displayValue($arwrk);
                    } else {
                        $this->Component_Display_Size1->ViewValue = $this->Component_Display_Size1->CurrentValue;
                    }
                }
            } else {
                $this->Component_Display_Size1->ViewValue = null;
            }

            // Component_Keyboard_Layout1
            $curVal = strval($this->Component_Keyboard_Layout1->CurrentValue);
            if ($curVal != "") {
                $this->Component_Keyboard_Layout1->ViewValue = $this->Component_Keyboard_Layout1->lookupCacheOption($curVal);
                if ($this->Component_Keyboard_Layout1->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Keyboard Layout`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Keyboard_Layout1->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Keyboard_Layout1->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Keyboard_Layout1->ViewValue = $this->Component_Keyboard_Layout1->displayValue($arwrk);
                    } else {
                        $this->Component_Keyboard_Layout1->ViewValue = $this->Component_Keyboard_Layout1->CurrentValue;
                    }
                }
            } else {
                $this->Component_Keyboard_Layout1->ViewValue = null;
            }

            // Component_Type2
            $curVal = strval($this->Component_Type2->CurrentValue);
            if ($curVal != "") {
                $this->Component_Type2->ViewValue = $this->Component_Type2->lookupCacheOption($curVal);
                if ($this->Component_Type2->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Type`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Type2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Type2->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Type2->ViewValue = $this->Component_Type2->displayValue($arwrk);
                    } else {
                        $this->Component_Type2->ViewValue = $this->Component_Type2->CurrentValue;
                    }
                }
            } else {
                $this->Component_Type2->ViewValue = null;
            }

            // Component_Category2
            $curVal = strval($this->Component_Category2->CurrentValue);
            if ($curVal != "") {
                $this->Component_Category2->ViewValue = $this->Component_Category2->lookupCacheOption($curVal);
                if ($this->Component_Category2->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Category`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Category2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Category2->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Category2->ViewValue = $this->Component_Category2->displayValue($arwrk);
                    } else {
                        $this->Component_Category2->ViewValue = $this->Component_Category2->CurrentValue;
                    }
                }
            } else {
                $this->Component_Category2->ViewValue = null;
            }

            // Component_Make2
            $curVal = strval($this->Component_Make2->CurrentValue);
            if ($curVal != "") {
                $this->Component_Make2->ViewValue = $this->Component_Make2->lookupCacheOption($curVal);
                if ($this->Component_Make2->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Make`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Make2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Make2->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Make2->ViewValue = $this->Component_Make2->displayValue($arwrk);
                    } else {
                        $this->Component_Make2->ViewValue = $this->Component_Make2->CurrentValue;
                    }
                }
            } else {
                $this->Component_Make2->ViewValue = null;
            }

            // Component_Model2
            $curVal = strval($this->Component_Model2->CurrentValue);
            if ($curVal != "") {
                $this->Component_Model2->ViewValue = $this->Component_Model2->lookupCacheOption($curVal);
                if ($this->Component_Model2->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Model`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Model2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Model2->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Model2->ViewValue = $this->Component_Model2->displayValue($arwrk);
                    } else {
                        $this->Component_Model2->ViewValue = $this->Component_Model2->CurrentValue;
                    }
                }
            } else {
                $this->Component_Model2->ViewValue = null;
            }

            // Component_Serial_Number2
            $this->Component_Serial_Number2->ViewValue = $this->Component_Serial_Number2->CurrentValue;

            // Component_Display_Size2
            $curVal = strval($this->Component_Display_Size2->CurrentValue);
            if ($curVal != "") {
                $this->Component_Display_Size2->ViewValue = $this->Component_Display_Size2->lookupCacheOption($curVal);
                if ($this->Component_Display_Size2->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Display Size`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Display_Size2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Display_Size2->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Display_Size2->ViewValue = $this->Component_Display_Size2->displayValue($arwrk);
                    } else {
                        $this->Component_Display_Size2->ViewValue = $this->Component_Display_Size2->CurrentValue;
                    }
                }
            } else {
                $this->Component_Display_Size2->ViewValue = null;
            }

            // Component_Keyboard_Layout2
            $curVal = strval($this->Component_Keyboard_Layout2->CurrentValue);
            if ($curVal != "") {
                $this->Component_Keyboard_Layout2->ViewValue = $this->Component_Keyboard_Layout2->lookupCacheOption($curVal);
                if ($this->Component_Keyboard_Layout2->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`Component Keyboard Layout`", "=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->Component_Keyboard_Layout2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Component_Keyboard_Layout2->Lookup->renderViewRow($rswrk[0]);
                        $this->Component_Keyboard_Layout2->ViewValue = $this->Component_Keyboard_Layout2->displayValue($arwrk);
                    } else {
                        $this->Component_Keyboard_Layout2->ViewValue = $this->Component_Keyboard_Layout2->CurrentValue;
                    }
                }
            } else {
                $this->Component_Keyboard_Layout2->ViewValue = null;
            }

            // Workstation_Name
            $this->Workstation_Name->HrefValue = "";
            $this->Workstation_Name->TooltipValue = "";

            // Workstation_Remark
            $this->Workstation_Remark->HrefValue = "";
            $this->Workstation_Remark->TooltipValue = "";

            // User_Email
            $this->User_Email->HrefValue = "";
            $this->User_Email->TooltipValue = "";

            // User_Name
            $this->User_Name->HrefValue = "";
            $this->User_Name->TooltipValue = "";

            // User_Employee_Number
            $this->User_Employee_Number->HrefValue = "";
            $this->User_Employee_Number->TooltipValue = "";

            // User_Phone_Number
            $this->User_Phone_Number->HrefValue = "";
            $this->User_Phone_Number->TooltipValue = "";

            // Address_Name
            $this->Address_Name->HrefValue = "";
            $this->Address_Name->TooltipValue = "";

            // Address_Street
            $this->Address_Street->HrefValue = "";
            $this->Address_Street->TooltipValue = "";

            // Address_Zipcode
            $this->Address_Zipcode->HrefValue = "";
            $this->Address_Zipcode->TooltipValue = "";

            // Address_City
            $this->Address_City->HrefValue = "";
            $this->Address_City->TooltipValue = "";

            // Address_Country
            $this->Address_Country->HrefValue = "";
            $this->Address_Country->TooltipValue = "";

            // Component_Type
            $this->Component_Type->HrefValue = "";
            $this->Component_Type->TooltipValue = "";

            // Component_Category
            $this->Component_Category->HrefValue = "";
            $this->Component_Category->TooltipValue = "";

            // Component_Make
            $this->Component_Make->HrefValue = "";
            $this->Component_Make->TooltipValue = "";

            // Component_Model
            $this->Component_Model->HrefValue = "";
            $this->Component_Model->TooltipValue = "";

            // Component_Serial_Number
            $this->Component_Serial_Number->HrefValue = "";
            $this->Component_Serial_Number->TooltipValue = "";

            // Component_Display_Size
            $this->Component_Display_Size->HrefValue = "";
            $this->Component_Display_Size->TooltipValue = "";

            // Component_Keyboard_Layout
            $this->Component_Keyboard_Layout->HrefValue = "";
            $this->Component_Keyboard_Layout->TooltipValue = "";

            // Component_Type1
            $this->Component_Type1->HrefValue = "";
            $this->Component_Type1->TooltipValue = "";

            // Component_Category1
            $this->Component_Category1->HrefValue = "";
            $this->Component_Category1->TooltipValue = "";

            // Component_Make1
            $this->Component_Make1->HrefValue = "";
            $this->Component_Make1->TooltipValue = "";

            // Component_Model1
            $this->Component_Model1->HrefValue = "";
            $this->Component_Model1->TooltipValue = "";

            // Component_Serial_Number1
            $this->Component_Serial_Number1->HrefValue = "";
            $this->Component_Serial_Number1->TooltipValue = "";

            // Component_Display_Size1
            $this->Component_Display_Size1->HrefValue = "";
            $this->Component_Display_Size1->TooltipValue = "";

            // Component_Keyboard_Layout1
            $this->Component_Keyboard_Layout1->HrefValue = "";
            $this->Component_Keyboard_Layout1->TooltipValue = "";

            // Component_Type2
            $this->Component_Type2->HrefValue = "";
            $this->Component_Type2->TooltipValue = "";

            // Component_Category2
            $this->Component_Category2->HrefValue = "";
            $this->Component_Category2->TooltipValue = "";

            // Component_Make2
            $this->Component_Make2->HrefValue = "";
            $this->Component_Make2->TooltipValue = "";

            // Component_Model2
            $this->Component_Model2->HrefValue = "";
            $this->Component_Model2->TooltipValue = "";

            // Component_Serial_Number2
            $this->Component_Serial_Number2->HrefValue = "";
            $this->Component_Serial_Number2->TooltipValue = "";

            // Component_Display_Size2
            $this->Component_Display_Size2->HrefValue = "";
            $this->Component_Display_Size2->TooltipValue = "";

            // Component_Keyboard_Layout2
            $this->Component_Keyboard_Layout2->HrefValue = "";
            $this->Component_Keyboard_Layout2->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAllAssociative($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }
        if ($this->UseTransaction) {
            $conn->beginTransaction();
        }

        // Clone old rows
        $rsold = $rows;
        $successKeys = [];
        $failKeys = [];
        foreach ($rsold as $row) {
            $thisKey = "";
            if ($thisKey != "") {
                $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
            }
            $thisKey .= $row['id'];

            // Call row deleting event
            $deleteRow = $this->rowDeleting($row);
            if ($deleteRow) { // Delete
                $deleteRow = $this->delete($row);
                if (!$deleteRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            }
            if ($deleteRow === false) {
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }

                // Call Row Deleted event
                $this->rowDeleted($row);
                $successKeys[] = $thisKey;
            }
        }

        // Any records deleted
        $deleteRows = count($successKeys) > 0;
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }
        if ($deleteRows) {
            if ($this->UseTransaction) { // Commit transaction
                $conn->commit();
            }

            // Set warning message if delete some records failed
            if (count($failKeys) > 0) {
                $this->setWarningMessage(str_replace("%k", explode(", ", $failKeys), $Language->phrase("DeleteRecordsFailed")));
            }
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $conn->rollback();
            }
        }

        // Write JSON response
        if ((IsJsonResponse() || ConvertToBool(Param("infinitescroll"))) && $deleteRows) {
            $rows = $this->getRecordsFromRecordset($rsold);
            $table = $this->TableVar;
            if (Route(2) !== null) { // Single delete
                $rows = $rows[0]; // Return object
            }
            WriteJson(["success" => true, "action" => Config("API_DELETE_ACTION"), $table => $rows]);
        }
        return $deleteRows;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("workstationlist"), "", $this->TableVar, true);
        $pageId = "delete";
        $Breadcrumb->add("delete", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_Component_Type":
                    break;
                case "x_Component_Category":
                    break;
                case "x_Component_Make":
                    break;
                case "x_Component_Model":
                    break;
                case "x_Component_Type1":
                    break;
                case "x_Component_Display_Size1":
                    break;
                case "x_Component_Keyboard_Layout1":
                    break;
                case "x_Component_Type2":
                    break;
                case "x_Component_Category2":
                    break;
                case "x_Component_Make2":
                    break;
                case "x_Component_Model2":
                    break;
                case "x_Component_Display_Size2":
                    break;
                case "x_Component_Keyboard_Layout2":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }
}
