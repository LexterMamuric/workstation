<?php

namespace WorkStationDB\project3;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class WorkstationUpdate extends Workstation
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "WorkstationUpdate";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "workstationupdate";

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
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-update-table";

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

            // Handle modal response (Assume return to modal for simplicity)
            if ($this->IsModal) { // Show as modal
                $result = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page => View page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = $pageName == "workstationview"; // If View page, no primary button
                } else { // List page
                    // $result["list"] = $this->PageID == "search"; // Refresh List page if current page is Search page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
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

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;
        $name = $ar["name"] ?? Post("name");
        $isQuery = ContainsString($name, "query_builder_rule");
        if ($isQuery) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-update-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $RecKeys;
    public $Disabled;
    public $UpdateCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Create form object
        $CurrentForm = new HttpForm();
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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Try to load keys from list form
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        if (Post("action") !== null && Post("action") !== "") {
            // Get action
            $this->CurrentAction = Post("action");
            $this->loadFormValues(); // Get form values

            // Validate form
            if (!$this->validateForm()) {
                $this->CurrentAction = "show"; // Form error, reset action
                if (!$this->hasInvalidFields()) { // No fields selected
                    $this->setFailureMessage($Language->phrase("NoFieldSelected"));
                }
            }
        } else {
            $this->loadMultiUpdateValues(); // Load initial values to form
        }
        if (count($this->RecKeys) <= 0) {
            $this->terminate("workstationlist"); // No records selected, return to list
            return;
        }
        if ($this->isUpdate()) {
                if ($this->updateRows()) {
                    // Do not return Json for UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                    }
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up update success message
                    }
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson([ "success" => false, "error" => $this->getFailureMessage() ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->restoreFormValues(); // Restore form values
                }
        }

        // Render row
        if ($this->isConfirm()) { // Confirm page
            $this->RowType = ROWTYPE_VIEW; // Render view
            $this->Disabled = " disabled";
        } else {
            $this->RowType = ROWTYPE_EDIT; // Render edit
            $this->Disabled = "";
        }
        $this->resetAttributes();
        $this->renderRow();

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

    // Load initial values to form if field values are identical in all selected records
    protected function loadMultiUpdateValues()
    {
        $this->CurrentFilter = $this->getFilterFromRecordKeys();

        // Load recordset
        if ($rs = $this->loadRecordset()) {
            $i = 1;
            while (!$rs->EOF) {
                if ($i == 1) {
                    $this->Workstation_Name->setDbValue($rs->fields['Workstation_Name']);
                    $this->Workstation_Remark->setDbValue($rs->fields['Workstation_Remark']);
                    $this->User_Email->setDbValue($rs->fields['User_Email']);
                    $this->User_Name->setDbValue($rs->fields['User_Name']);
                    $this->User_Employee_Number->setDbValue($rs->fields['User_Employee_Number']);
                    $this->User_Phone_Number->setDbValue($rs->fields['User_Phone_Number']);
                    $this->Address_Name->setDbValue($rs->fields['Address_Name']);
                    $this->Address_Street->setDbValue($rs->fields['Address_Street']);
                    $this->Address_Zipcode->setDbValue($rs->fields['Address_Zipcode']);
                    $this->Address_City->setDbValue($rs->fields['Address_City']);
                    $this->Address_Country->setDbValue($rs->fields['Address_Country']);
                    $this->Component_Type->setDbValue($rs->fields['Component_Type']);
                    $this->Component_Category->setDbValue($rs->fields['Component_Category']);
                    $this->Component_Make->setDbValue($rs->fields['Component_Make']);
                    $this->Component_Model->setDbValue($rs->fields['Component_Model']);
                    $this->Component_Serial_Number->setDbValue($rs->fields['Component_Serial_Number']);
                    $this->Component_Display_Size->setDbValue($rs->fields['Component_Display_Size']);
                    $this->Component_Keyboard_Layout->setDbValue($rs->fields['Component_Keyboard_Layout']);
                    $this->Component_Type1->setDbValue($rs->fields['Component_Type1']);
                    $this->Component_Category1->setDbValue($rs->fields['Component_Category1']);
                    $this->Component_Make1->setDbValue($rs->fields['Component_Make1']);
                    $this->Component_Model1->setDbValue($rs->fields['Component_Model1']);
                    $this->Component_Serial_Number1->setDbValue($rs->fields['Component_Serial_Number1']);
                    $this->Component_Display_Size1->setDbValue($rs->fields['Component_Display_Size1']);
                    $this->Component_Keyboard_Layout1->setDbValue($rs->fields['Component_Keyboard_Layout1']);
                    $this->Component_Type2->setDbValue($rs->fields['Component_Type2']);
                    $this->Component_Category2->setDbValue($rs->fields['Component_Category2']);
                    $this->Component_Make2->setDbValue($rs->fields['Component_Make2']);
                    $this->Component_Model2->setDbValue($rs->fields['Component_Model2']);
                    $this->Component_Serial_Number2->setDbValue($rs->fields['Component_Serial_Number2']);
                    $this->Component_Display_Size2->setDbValue($rs->fields['Component_Display_Size2']);
                    $this->Component_Keyboard_Layout2->setDbValue($rs->fields['Component_Keyboard_Layout2']);
                } else {
                    if (!CompareValue($this->Workstation_Name->DbValue, $rs->fields['Workstation_Name'])) {
                        $this->Workstation_Name->CurrentValue = null;
                    }
                    if (!CompareValue($this->Workstation_Remark->DbValue, $rs->fields['Workstation_Remark'])) {
                        $this->Workstation_Remark->CurrentValue = null;
                    }
                    if (!CompareValue($this->User_Email->DbValue, $rs->fields['User_Email'])) {
                        $this->User_Email->CurrentValue = null;
                    }
                    if (!CompareValue($this->User_Name->DbValue, $rs->fields['User_Name'])) {
                        $this->User_Name->CurrentValue = null;
                    }
                    if (!CompareValue($this->User_Employee_Number->DbValue, $rs->fields['User_Employee_Number'])) {
                        $this->User_Employee_Number->CurrentValue = null;
                    }
                    if (!CompareValue($this->User_Phone_Number->DbValue, $rs->fields['User_Phone_Number'])) {
                        $this->User_Phone_Number->CurrentValue = null;
                    }
                    if (!CompareValue($this->Address_Name->DbValue, $rs->fields['Address_Name'])) {
                        $this->Address_Name->CurrentValue = null;
                    }
                    if (!CompareValue($this->Address_Street->DbValue, $rs->fields['Address_Street'])) {
                        $this->Address_Street->CurrentValue = null;
                    }
                    if (!CompareValue($this->Address_Zipcode->DbValue, $rs->fields['Address_Zipcode'])) {
                        $this->Address_Zipcode->CurrentValue = null;
                    }
                    if (!CompareValue($this->Address_City->DbValue, $rs->fields['Address_City'])) {
                        $this->Address_City->CurrentValue = null;
                    }
                    if (!CompareValue($this->Address_Country->DbValue, $rs->fields['Address_Country'])) {
                        $this->Address_Country->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Type->DbValue, $rs->fields['Component_Type'])) {
                        $this->Component_Type->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Category->DbValue, $rs->fields['Component_Category'])) {
                        $this->Component_Category->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Make->DbValue, $rs->fields['Component_Make'])) {
                        $this->Component_Make->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Model->DbValue, $rs->fields['Component_Model'])) {
                        $this->Component_Model->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Serial_Number->DbValue, $rs->fields['Component_Serial_Number'])) {
                        $this->Component_Serial_Number->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Display_Size->DbValue, $rs->fields['Component_Display_Size'])) {
                        $this->Component_Display_Size->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Keyboard_Layout->DbValue, $rs->fields['Component_Keyboard_Layout'])) {
                        $this->Component_Keyboard_Layout->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Type1->DbValue, $rs->fields['Component_Type1'])) {
                        $this->Component_Type1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Category1->DbValue, $rs->fields['Component_Category1'])) {
                        $this->Component_Category1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Make1->DbValue, $rs->fields['Component_Make1'])) {
                        $this->Component_Make1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Model1->DbValue, $rs->fields['Component_Model1'])) {
                        $this->Component_Model1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Serial_Number1->DbValue, $rs->fields['Component_Serial_Number1'])) {
                        $this->Component_Serial_Number1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Display_Size1->DbValue, $rs->fields['Component_Display_Size1'])) {
                        $this->Component_Display_Size1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Keyboard_Layout1->DbValue, $rs->fields['Component_Keyboard_Layout1'])) {
                        $this->Component_Keyboard_Layout1->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Type2->DbValue, $rs->fields['Component_Type2'])) {
                        $this->Component_Type2->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Category2->DbValue, $rs->fields['Component_Category2'])) {
                        $this->Component_Category2->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Make2->DbValue, $rs->fields['Component_Make2'])) {
                        $this->Component_Make2->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Model2->DbValue, $rs->fields['Component_Model2'])) {
                        $this->Component_Model2->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Serial_Number2->DbValue, $rs->fields['Component_Serial_Number2'])) {
                        $this->Component_Serial_Number2->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Display_Size2->DbValue, $rs->fields['Component_Display_Size2'])) {
                        $this->Component_Display_Size2->CurrentValue = null;
                    }
                    if (!CompareValue($this->Component_Keyboard_Layout2->DbValue, $rs->fields['Component_Keyboard_Layout2'])) {
                        $this->Component_Keyboard_Layout2->CurrentValue = null;
                    }
                }
                $i++;
                $rs->moveNext();
            }
            $rs->close();
        }
    }

    // Set up key value
    protected function setupKeyValues($key)
    {
        $keyFld = $key;
        if (!is_numeric($keyFld)) {
            return false;
        }
        $this->id->OldValue = $keyFld;
        return true;
    }

    // Update all selected rows
    protected function updateRows()
    {
        global $Language;
        $conn = $this->getConnection();
        if ($this->UseTransaction) {
            $conn->beginTransaction();
        }

        // Get old records
        $this->CurrentFilter = $this->getFilterFromRecordKeys(false);
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAllAssociative($sql);

        // Update all rows
        $successKeys = [];
        $failKeys = [];
        foreach ($this->RecKeys as $reckey) {
            if ($this->setupKeyValues($reckey)) {
                $thisKey = $reckey;
                $this->SendEmail = false; // Do not send email on update success
                $this->UpdateCount += 1; // Update record count for records being updated
                $rowUpdated = $this->editRow(); // Update this row
            } else {
                $rowUpdated = false;
            }
            if (!$rowUpdated) {
                if ($this->UseTransaction) { // Update failed
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                $successKeys[] = $thisKey;
            }
        }

        // Check if any rows updated
        if (count($successKeys) > 0) {
            if ($this->UseTransaction) { // Commit transaction
                $conn->commit();
            }

            // Set warning message if update some records failed
            if (count($failKeys) > 0) {
                $this->setWarningMessage(str_replace("%k", explode(", ", $failKeys), $Language->phrase("UpdateSomeRecordsFailed")));
            }

            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);
            return true;
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $conn->rollback();
            }
            return false;
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Workstation_Name' first before field var 'x_Workstation_Name'
        $val = $CurrentForm->hasValue("Workstation_Name") ? $CurrentForm->getValue("Workstation_Name") : $CurrentForm->getValue("x_Workstation_Name");
        if (!$this->Workstation_Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Workstation_Name->Visible = false; // Disable update for API request
            } else {
                $this->Workstation_Name->setFormValue($val);
            }
        }
        $this->Workstation_Name->MultiUpdate = $CurrentForm->getValue("u_Workstation_Name");

        // Check field name 'Workstation_Remark' first before field var 'x_Workstation_Remark'
        $val = $CurrentForm->hasValue("Workstation_Remark") ? $CurrentForm->getValue("Workstation_Remark") : $CurrentForm->getValue("x_Workstation_Remark");
        if (!$this->Workstation_Remark->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Workstation_Remark->Visible = false; // Disable update for API request
            } else {
                $this->Workstation_Remark->setFormValue($val);
            }
        }
        $this->Workstation_Remark->MultiUpdate = $CurrentForm->getValue("u_Workstation_Remark");

        // Check field name 'User_Email' first before field var 'x_User_Email'
        $val = $CurrentForm->hasValue("User_Email") ? $CurrentForm->getValue("User_Email") : $CurrentForm->getValue("x_User_Email");
        if (!$this->User_Email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->User_Email->Visible = false; // Disable update for API request
            } else {
                $this->User_Email->setFormValue($val);
            }
        }
        $this->User_Email->MultiUpdate = $CurrentForm->getValue("u_User_Email");

        // Check field name 'User_Name' first before field var 'x_User_Name'
        $val = $CurrentForm->hasValue("User_Name") ? $CurrentForm->getValue("User_Name") : $CurrentForm->getValue("x_User_Name");
        if (!$this->User_Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->User_Name->Visible = false; // Disable update for API request
            } else {
                $this->User_Name->setFormValue($val);
            }
        }
        $this->User_Name->MultiUpdate = $CurrentForm->getValue("u_User_Name");

        // Check field name 'User_Employee_Number' first before field var 'x_User_Employee_Number'
        $val = $CurrentForm->hasValue("User_Employee_Number") ? $CurrentForm->getValue("User_Employee_Number") : $CurrentForm->getValue("x_User_Employee_Number");
        if (!$this->User_Employee_Number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->User_Employee_Number->Visible = false; // Disable update for API request
            } else {
                $this->User_Employee_Number->setFormValue($val);
            }
        }
        $this->User_Employee_Number->MultiUpdate = $CurrentForm->getValue("u_User_Employee_Number");

        // Check field name 'User_Phone_Number' first before field var 'x_User_Phone_Number'
        $val = $CurrentForm->hasValue("User_Phone_Number") ? $CurrentForm->getValue("User_Phone_Number") : $CurrentForm->getValue("x_User_Phone_Number");
        if (!$this->User_Phone_Number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->User_Phone_Number->Visible = false; // Disable update for API request
            } else {
                $this->User_Phone_Number->setFormValue($val);
            }
        }
        $this->User_Phone_Number->MultiUpdate = $CurrentForm->getValue("u_User_Phone_Number");

        // Check field name 'Address_Name' first before field var 'x_Address_Name'
        $val = $CurrentForm->hasValue("Address_Name") ? $CurrentForm->getValue("Address_Name") : $CurrentForm->getValue("x_Address_Name");
        if (!$this->Address_Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address_Name->Visible = false; // Disable update for API request
            } else {
                $this->Address_Name->setFormValue($val);
            }
        }
        $this->Address_Name->MultiUpdate = $CurrentForm->getValue("u_Address_Name");

        // Check field name 'Address_Street' first before field var 'x_Address_Street'
        $val = $CurrentForm->hasValue("Address_Street") ? $CurrentForm->getValue("Address_Street") : $CurrentForm->getValue("x_Address_Street");
        if (!$this->Address_Street->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address_Street->Visible = false; // Disable update for API request
            } else {
                $this->Address_Street->setFormValue($val);
            }
        }
        $this->Address_Street->MultiUpdate = $CurrentForm->getValue("u_Address_Street");

        // Check field name 'Address_Zipcode' first before field var 'x_Address_Zipcode'
        $val = $CurrentForm->hasValue("Address_Zipcode") ? $CurrentForm->getValue("Address_Zipcode") : $CurrentForm->getValue("x_Address_Zipcode");
        if (!$this->Address_Zipcode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address_Zipcode->Visible = false; // Disable update for API request
            } else {
                $this->Address_Zipcode->setFormValue($val);
            }
        }
        $this->Address_Zipcode->MultiUpdate = $CurrentForm->getValue("u_Address_Zipcode");

        // Check field name 'Address_City' first before field var 'x_Address_City'
        $val = $CurrentForm->hasValue("Address_City") ? $CurrentForm->getValue("Address_City") : $CurrentForm->getValue("x_Address_City");
        if (!$this->Address_City->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address_City->Visible = false; // Disable update for API request
            } else {
                $this->Address_City->setFormValue($val);
            }
        }
        $this->Address_City->MultiUpdate = $CurrentForm->getValue("u_Address_City");

        // Check field name 'Address_Country' first before field var 'x_Address_Country'
        $val = $CurrentForm->hasValue("Address_Country") ? $CurrentForm->getValue("Address_Country") : $CurrentForm->getValue("x_Address_Country");
        if (!$this->Address_Country->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address_Country->Visible = false; // Disable update for API request
            } else {
                $this->Address_Country->setFormValue($val);
            }
        }
        $this->Address_Country->MultiUpdate = $CurrentForm->getValue("u_Address_Country");

        // Check field name 'Component_Type' first before field var 'x_Component_Type'
        $val = $CurrentForm->hasValue("Component_Type") ? $CurrentForm->getValue("Component_Type") : $CurrentForm->getValue("x_Component_Type");
        if (!$this->Component_Type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Type->Visible = false; // Disable update for API request
            } else {
                $this->Component_Type->setFormValue($val);
            }
        }
        $this->Component_Type->MultiUpdate = $CurrentForm->getValue("u_Component_Type");

        // Check field name 'Component_Category' first before field var 'x_Component_Category'
        $val = $CurrentForm->hasValue("Component_Category") ? $CurrentForm->getValue("Component_Category") : $CurrentForm->getValue("x_Component_Category");
        if (!$this->Component_Category->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Category->Visible = false; // Disable update for API request
            } else {
                $this->Component_Category->setFormValue($val);
            }
        }
        $this->Component_Category->MultiUpdate = $CurrentForm->getValue("u_Component_Category");

        // Check field name 'Component_Make' first before field var 'x_Component_Make'
        $val = $CurrentForm->hasValue("Component_Make") ? $CurrentForm->getValue("Component_Make") : $CurrentForm->getValue("x_Component_Make");
        if (!$this->Component_Make->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Make->Visible = false; // Disable update for API request
            } else {
                $this->Component_Make->setFormValue($val);
            }
        }
        $this->Component_Make->MultiUpdate = $CurrentForm->getValue("u_Component_Make");

        // Check field name 'Component_Model' first before field var 'x_Component_Model'
        $val = $CurrentForm->hasValue("Component_Model") ? $CurrentForm->getValue("Component_Model") : $CurrentForm->getValue("x_Component_Model");
        if (!$this->Component_Model->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Model->Visible = false; // Disable update for API request
            } else {
                $this->Component_Model->setFormValue($val);
            }
        }
        $this->Component_Model->MultiUpdate = $CurrentForm->getValue("u_Component_Model");

        // Check field name 'Component_Serial_Number' first before field var 'x_Component_Serial_Number'
        $val = $CurrentForm->hasValue("Component_Serial_Number") ? $CurrentForm->getValue("Component_Serial_Number") : $CurrentForm->getValue("x_Component_Serial_Number");
        if (!$this->Component_Serial_Number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Serial_Number->Visible = false; // Disable update for API request
            } else {
                $this->Component_Serial_Number->setFormValue($val);
            }
        }
        $this->Component_Serial_Number->MultiUpdate = $CurrentForm->getValue("u_Component_Serial_Number");

        // Check field name 'Component_Display_Size' first before field var 'x_Component_Display_Size'
        $val = $CurrentForm->hasValue("Component_Display_Size") ? $CurrentForm->getValue("Component_Display_Size") : $CurrentForm->getValue("x_Component_Display_Size");
        if (!$this->Component_Display_Size->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Display_Size->Visible = false; // Disable update for API request
            } else {
                $this->Component_Display_Size->setFormValue($val);
            }
        }
        $this->Component_Display_Size->MultiUpdate = $CurrentForm->getValue("u_Component_Display_Size");

        // Check field name 'Component_Keyboard_Layout' first before field var 'x_Component_Keyboard_Layout'
        $val = $CurrentForm->hasValue("Component_Keyboard_Layout") ? $CurrentForm->getValue("Component_Keyboard_Layout") : $CurrentForm->getValue("x_Component_Keyboard_Layout");
        if (!$this->Component_Keyboard_Layout->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Keyboard_Layout->Visible = false; // Disable update for API request
            } else {
                $this->Component_Keyboard_Layout->setFormValue($val);
            }
        }
        $this->Component_Keyboard_Layout->MultiUpdate = $CurrentForm->getValue("u_Component_Keyboard_Layout");

        // Check field name 'Component_Type1' first before field var 'x_Component_Type1'
        $val = $CurrentForm->hasValue("Component_Type1") ? $CurrentForm->getValue("Component_Type1") : $CurrentForm->getValue("x_Component_Type1");
        if (!$this->Component_Type1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Type1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Type1->setFormValue($val);
            }
        }
        $this->Component_Type1->MultiUpdate = $CurrentForm->getValue("u_Component_Type1");

        // Check field name 'Component_Category1' first before field var 'x_Component_Category1'
        $val = $CurrentForm->hasValue("Component_Category1") ? $CurrentForm->getValue("Component_Category1") : $CurrentForm->getValue("x_Component_Category1");
        if (!$this->Component_Category1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Category1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Category1->setFormValue($val);
            }
        }
        $this->Component_Category1->MultiUpdate = $CurrentForm->getValue("u_Component_Category1");

        // Check field name 'Component_Make1' first before field var 'x_Component_Make1'
        $val = $CurrentForm->hasValue("Component_Make1") ? $CurrentForm->getValue("Component_Make1") : $CurrentForm->getValue("x_Component_Make1");
        if (!$this->Component_Make1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Make1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Make1->setFormValue($val);
            }
        }
        $this->Component_Make1->MultiUpdate = $CurrentForm->getValue("u_Component_Make1");

        // Check field name 'Component_Model1' first before field var 'x_Component_Model1'
        $val = $CurrentForm->hasValue("Component_Model1") ? $CurrentForm->getValue("Component_Model1") : $CurrentForm->getValue("x_Component_Model1");
        if (!$this->Component_Model1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Model1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Model1->setFormValue($val);
            }
        }
        $this->Component_Model1->MultiUpdate = $CurrentForm->getValue("u_Component_Model1");

        // Check field name 'Component_Serial_Number1' first before field var 'x_Component_Serial_Number1'
        $val = $CurrentForm->hasValue("Component_Serial_Number1") ? $CurrentForm->getValue("Component_Serial_Number1") : $CurrentForm->getValue("x_Component_Serial_Number1");
        if (!$this->Component_Serial_Number1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Serial_Number1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Serial_Number1->setFormValue($val);
            }
        }
        $this->Component_Serial_Number1->MultiUpdate = $CurrentForm->getValue("u_Component_Serial_Number1");

        // Check field name 'Component_Display_Size1' first before field var 'x_Component_Display_Size1'
        $val = $CurrentForm->hasValue("Component_Display_Size1") ? $CurrentForm->getValue("Component_Display_Size1") : $CurrentForm->getValue("x_Component_Display_Size1");
        if (!$this->Component_Display_Size1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Display_Size1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Display_Size1->setFormValue($val);
            }
        }
        $this->Component_Display_Size1->MultiUpdate = $CurrentForm->getValue("u_Component_Display_Size1");

        // Check field name 'Component_Keyboard_Layout1' first before field var 'x_Component_Keyboard_Layout1'
        $val = $CurrentForm->hasValue("Component_Keyboard_Layout1") ? $CurrentForm->getValue("Component_Keyboard_Layout1") : $CurrentForm->getValue("x_Component_Keyboard_Layout1");
        if (!$this->Component_Keyboard_Layout1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Keyboard_Layout1->Visible = false; // Disable update for API request
            } else {
                $this->Component_Keyboard_Layout1->setFormValue($val);
            }
        }
        $this->Component_Keyboard_Layout1->MultiUpdate = $CurrentForm->getValue("u_Component_Keyboard_Layout1");

        // Check field name 'Component_Type2' first before field var 'x_Component_Type2'
        $val = $CurrentForm->hasValue("Component_Type2") ? $CurrentForm->getValue("Component_Type2") : $CurrentForm->getValue("x_Component_Type2");
        if (!$this->Component_Type2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Type2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Type2->setFormValue($val);
            }
        }
        $this->Component_Type2->MultiUpdate = $CurrentForm->getValue("u_Component_Type2");

        // Check field name 'Component_Category2' first before field var 'x_Component_Category2'
        $val = $CurrentForm->hasValue("Component_Category2") ? $CurrentForm->getValue("Component_Category2") : $CurrentForm->getValue("x_Component_Category2");
        if (!$this->Component_Category2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Category2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Category2->setFormValue($val);
            }
        }
        $this->Component_Category2->MultiUpdate = $CurrentForm->getValue("u_Component_Category2");

        // Check field name 'Component_Make2' first before field var 'x_Component_Make2'
        $val = $CurrentForm->hasValue("Component_Make2") ? $CurrentForm->getValue("Component_Make2") : $CurrentForm->getValue("x_Component_Make2");
        if (!$this->Component_Make2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Make2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Make2->setFormValue($val);
            }
        }
        $this->Component_Make2->MultiUpdate = $CurrentForm->getValue("u_Component_Make2");

        // Check field name 'Component_Model2' first before field var 'x_Component_Model2'
        $val = $CurrentForm->hasValue("Component_Model2") ? $CurrentForm->getValue("Component_Model2") : $CurrentForm->getValue("x_Component_Model2");
        if (!$this->Component_Model2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Model2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Model2->setFormValue($val);
            }
        }
        $this->Component_Model2->MultiUpdate = $CurrentForm->getValue("u_Component_Model2");

        // Check field name 'Component_Serial_Number2' first before field var 'x_Component_Serial_Number2'
        $val = $CurrentForm->hasValue("Component_Serial_Number2") ? $CurrentForm->getValue("Component_Serial_Number2") : $CurrentForm->getValue("x_Component_Serial_Number2");
        if (!$this->Component_Serial_Number2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Serial_Number2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Serial_Number2->setFormValue($val);
            }
        }
        $this->Component_Serial_Number2->MultiUpdate = $CurrentForm->getValue("u_Component_Serial_Number2");

        // Check field name 'Component_Display_Size2' first before field var 'x_Component_Display_Size2'
        $val = $CurrentForm->hasValue("Component_Display_Size2") ? $CurrentForm->getValue("Component_Display_Size2") : $CurrentForm->getValue("x_Component_Display_Size2");
        if (!$this->Component_Display_Size2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Display_Size2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Display_Size2->setFormValue($val);
            }
        }
        $this->Component_Display_Size2->MultiUpdate = $CurrentForm->getValue("u_Component_Display_Size2");

        // Check field name 'Component_Keyboard_Layout2' first before field var 'x_Component_Keyboard_Layout2'
        $val = $CurrentForm->hasValue("Component_Keyboard_Layout2") ? $CurrentForm->getValue("Component_Keyboard_Layout2") : $CurrentForm->getValue("x_Component_Keyboard_Layout2");
        if (!$this->Component_Keyboard_Layout2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Component_Keyboard_Layout2->Visible = false; // Disable update for API request
            } else {
                $this->Component_Keyboard_Layout2->setFormValue($val);
            }
        }
        $this->Component_Keyboard_Layout2->MultiUpdate = $CurrentForm->getValue("u_Component_Keyboard_Layout2");

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->Workstation_Name->CurrentValue = $this->Workstation_Name->FormValue;
        $this->Workstation_Remark->CurrentValue = $this->Workstation_Remark->FormValue;
        $this->User_Email->CurrentValue = $this->User_Email->FormValue;
        $this->User_Name->CurrentValue = $this->User_Name->FormValue;
        $this->User_Employee_Number->CurrentValue = $this->User_Employee_Number->FormValue;
        $this->User_Phone_Number->CurrentValue = $this->User_Phone_Number->FormValue;
        $this->Address_Name->CurrentValue = $this->Address_Name->FormValue;
        $this->Address_Street->CurrentValue = $this->Address_Street->FormValue;
        $this->Address_Zipcode->CurrentValue = $this->Address_Zipcode->FormValue;
        $this->Address_City->CurrentValue = $this->Address_City->FormValue;
        $this->Address_Country->CurrentValue = $this->Address_Country->FormValue;
        $this->Component_Type->CurrentValue = $this->Component_Type->FormValue;
        $this->Component_Category->CurrentValue = $this->Component_Category->FormValue;
        $this->Component_Make->CurrentValue = $this->Component_Make->FormValue;
        $this->Component_Model->CurrentValue = $this->Component_Model->FormValue;
        $this->Component_Serial_Number->CurrentValue = $this->Component_Serial_Number->FormValue;
        $this->Component_Display_Size->CurrentValue = $this->Component_Display_Size->FormValue;
        $this->Component_Keyboard_Layout->CurrentValue = $this->Component_Keyboard_Layout->FormValue;
        $this->Component_Type1->CurrentValue = $this->Component_Type1->FormValue;
        $this->Component_Category1->CurrentValue = $this->Component_Category1->FormValue;
        $this->Component_Make1->CurrentValue = $this->Component_Make1->FormValue;
        $this->Component_Model1->CurrentValue = $this->Component_Model1->FormValue;
        $this->Component_Serial_Number1->CurrentValue = $this->Component_Serial_Number1->FormValue;
        $this->Component_Display_Size1->CurrentValue = $this->Component_Display_Size1->FormValue;
        $this->Component_Keyboard_Layout1->CurrentValue = $this->Component_Keyboard_Layout1->FormValue;
        $this->Component_Type2->CurrentValue = $this->Component_Type2->FormValue;
        $this->Component_Category2->CurrentValue = $this->Component_Category2->FormValue;
        $this->Component_Make2->CurrentValue = $this->Component_Make2->FormValue;
        $this->Component_Model2->CurrentValue = $this->Component_Model2->FormValue;
        $this->Component_Serial_Number2->CurrentValue = $this->Component_Serial_Number2->FormValue;
        $this->Component_Display_Size2->CurrentValue = $this->Component_Display_Size2->FormValue;
        $this->Component_Keyboard_Layout2->CurrentValue = $this->Component_Keyboard_Layout2->FormValue;
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
        $this->id->RowCssClass = "row";

        // Workstation_Name
        $this->Workstation_Name->RowCssClass = "row";

        // Workstation_Remark
        $this->Workstation_Remark->RowCssClass = "row";

        // User_Email
        $this->User_Email->RowCssClass = "row";

        // User_Name
        $this->User_Name->RowCssClass = "row";

        // User_Employee_Number
        $this->User_Employee_Number->RowCssClass = "row";

        // User_Phone_Number
        $this->User_Phone_Number->RowCssClass = "row";

        // Address_Name
        $this->Address_Name->RowCssClass = "row";

        // Address_Street
        $this->Address_Street->RowCssClass = "row";

        // Address_Zipcode
        $this->Address_Zipcode->RowCssClass = "row";

        // Address_City
        $this->Address_City->RowCssClass = "row";

        // Address_Country
        $this->Address_Country->RowCssClass = "row";

        // Component_Type
        $this->Component_Type->RowCssClass = "row";

        // Component_Category
        $this->Component_Category->RowCssClass = "row";

        // Component_Make
        $this->Component_Make->RowCssClass = "row";

        // Component_Model
        $this->Component_Model->RowCssClass = "row";

        // Component_Serial_Number
        $this->Component_Serial_Number->RowCssClass = "row";

        // Component_Display_Size
        $this->Component_Display_Size->RowCssClass = "row";

        // Component_Keyboard_Layout
        $this->Component_Keyboard_Layout->RowCssClass = "row";

        // Component_Type1
        $this->Component_Type1->RowCssClass = "row";

        // Component_Category1
        $this->Component_Category1->RowCssClass = "row";

        // Component_Make1
        $this->Component_Make1->RowCssClass = "row";

        // Component_Model1
        $this->Component_Model1->RowCssClass = "row";

        // Component_Serial_Number1
        $this->Component_Serial_Number1->RowCssClass = "row";

        // Component_Display_Size1
        $this->Component_Display_Size1->RowCssClass = "row";

        // Component_Keyboard_Layout1
        $this->Component_Keyboard_Layout1->RowCssClass = "row";

        // Component_Type2
        $this->Component_Type2->RowCssClass = "row";

        // Component_Category2
        $this->Component_Category2->RowCssClass = "row";

        // Component_Make2
        $this->Component_Make2->RowCssClass = "row";

        // Component_Model2
        $this->Component_Model2->RowCssClass = "row";

        // Component_Serial_Number2
        $this->Component_Serial_Number2->RowCssClass = "row";

        // Component_Display_Size2
        $this->Component_Display_Size2->RowCssClass = "row";

        // Component_Keyboard_Layout2
        $this->Component_Keyboard_Layout2->RowCssClass = "row";

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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // Workstation_Name
            $this->Workstation_Name->setupEditAttributes();
            if (!$this->Workstation_Name->Raw) {
                $this->Workstation_Name->CurrentValue = HtmlDecode($this->Workstation_Name->CurrentValue);
            }
            $this->Workstation_Name->EditValue = HtmlEncode($this->Workstation_Name->CurrentValue);
            $this->Workstation_Name->PlaceHolder = RemoveHtml($this->Workstation_Name->caption());

            // Workstation_Remark
            $this->Workstation_Remark->setupEditAttributes();
            if (!$this->Workstation_Remark->Raw) {
                $this->Workstation_Remark->CurrentValue = HtmlDecode($this->Workstation_Remark->CurrentValue);
            }
            $this->Workstation_Remark->EditValue = HtmlEncode($this->Workstation_Remark->CurrentValue);
            $this->Workstation_Remark->PlaceHolder = RemoveHtml($this->Workstation_Remark->caption());

            // User_Email
            $this->User_Email->setupEditAttributes();
            if (!$this->User_Email->Raw) {
                $this->User_Email->CurrentValue = HtmlDecode($this->User_Email->CurrentValue);
            }
            $this->User_Email->EditValue = HtmlEncode($this->User_Email->CurrentValue);
            $this->User_Email->PlaceHolder = RemoveHtml($this->User_Email->caption());

            // User_Name
            $this->User_Name->setupEditAttributes();
            if (!$this->User_Name->Raw) {
                $this->User_Name->CurrentValue = HtmlDecode($this->User_Name->CurrentValue);
            }
            $this->User_Name->EditValue = HtmlEncode($this->User_Name->CurrentValue);
            $this->User_Name->PlaceHolder = RemoveHtml($this->User_Name->caption());

            // User_Employee_Number
            $this->User_Employee_Number->setupEditAttributes();
            if (!$this->User_Employee_Number->Raw) {
                $this->User_Employee_Number->CurrentValue = HtmlDecode($this->User_Employee_Number->CurrentValue);
            }
            $this->User_Employee_Number->EditValue = HtmlEncode($this->User_Employee_Number->CurrentValue);
            $this->User_Employee_Number->PlaceHolder = RemoveHtml($this->User_Employee_Number->caption());

            // User_Phone_Number
            $this->User_Phone_Number->setupEditAttributes();
            if (!$this->User_Phone_Number->Raw) {
                $this->User_Phone_Number->CurrentValue = HtmlDecode($this->User_Phone_Number->CurrentValue);
            }
            $this->User_Phone_Number->EditValue = HtmlEncode($this->User_Phone_Number->CurrentValue);
            $this->User_Phone_Number->PlaceHolder = RemoveHtml($this->User_Phone_Number->caption());

            // Address_Name
            $this->Address_Name->setupEditAttributes();
            if (!$this->Address_Name->Raw) {
                $this->Address_Name->CurrentValue = HtmlDecode($this->Address_Name->CurrentValue);
            }
            $this->Address_Name->EditValue = HtmlEncode($this->Address_Name->CurrentValue);
            $this->Address_Name->PlaceHolder = RemoveHtml($this->Address_Name->caption());

            // Address_Street
            $this->Address_Street->setupEditAttributes();
            if (!$this->Address_Street->Raw) {
                $this->Address_Street->CurrentValue = HtmlDecode($this->Address_Street->CurrentValue);
            }
            $this->Address_Street->EditValue = HtmlEncode($this->Address_Street->CurrentValue);
            $this->Address_Street->PlaceHolder = RemoveHtml($this->Address_Street->caption());

            // Address_Zipcode
            $this->Address_Zipcode->setupEditAttributes();
            if (!$this->Address_Zipcode->Raw) {
                $this->Address_Zipcode->CurrentValue = HtmlDecode($this->Address_Zipcode->CurrentValue);
            }
            $this->Address_Zipcode->EditValue = HtmlEncode($this->Address_Zipcode->CurrentValue);
            $this->Address_Zipcode->PlaceHolder = RemoveHtml($this->Address_Zipcode->caption());

            // Address_City
            $this->Address_City->setupEditAttributes();
            if (!$this->Address_City->Raw) {
                $this->Address_City->CurrentValue = HtmlDecode($this->Address_City->CurrentValue);
            }
            $this->Address_City->EditValue = HtmlEncode($this->Address_City->CurrentValue);
            $this->Address_City->PlaceHolder = RemoveHtml($this->Address_City->caption());

            // Address_Country
            $this->Address_Country->setupEditAttributes();
            if (!$this->Address_Country->Raw) {
                $this->Address_Country->CurrentValue = HtmlDecode($this->Address_Country->CurrentValue);
            }
            $this->Address_Country->EditValue = HtmlEncode($this->Address_Country->CurrentValue);
            $this->Address_Country->PlaceHolder = RemoveHtml($this->Address_Country->caption());

            // Component_Type
            $this->Component_Type->setupEditAttributes();
            $curVal = trim(strval($this->Component_Type->CurrentValue));
            if ($curVal != "") {
                $this->Component_Type->ViewValue = $this->Component_Type->lookupCacheOption($curVal);
            } else {
                $this->Component_Type->ViewValue = $this->Component_Type->Lookup !== null && is_array($this->Component_Type->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Type->ViewValue !== null) { // Load from cache
                $this->Component_Type->EditValue = array_values($this->Component_Type->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Type`", "=", $this->Component_Type->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Type->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Type->EditValue = $arwrk;
            }
            $this->Component_Type->PlaceHolder = RemoveHtml($this->Component_Type->caption());

            // Component_Category
            $this->Component_Category->setupEditAttributes();
            $curVal = trim(strval($this->Component_Category->CurrentValue));
            if ($curVal != "") {
                $this->Component_Category->ViewValue = $this->Component_Category->lookupCacheOption($curVal);
            } else {
                $this->Component_Category->ViewValue = $this->Component_Category->Lookup !== null && is_array($this->Component_Category->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Category->ViewValue !== null) { // Load from cache
                $this->Component_Category->EditValue = array_values($this->Component_Category->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Category`", "=", $this->Component_Category->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Category->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Category->EditValue = $arwrk;
            }
            $this->Component_Category->PlaceHolder = RemoveHtml($this->Component_Category->caption());

            // Component_Make
            $this->Component_Make->setupEditAttributes();
            $curVal = trim(strval($this->Component_Make->CurrentValue));
            if ($curVal != "") {
                $this->Component_Make->ViewValue = $this->Component_Make->lookupCacheOption($curVal);
            } else {
                $this->Component_Make->ViewValue = $this->Component_Make->Lookup !== null && is_array($this->Component_Make->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Make->ViewValue !== null) { // Load from cache
                $this->Component_Make->EditValue = array_values($this->Component_Make->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Make`", "=", $this->Component_Make->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Make->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Make->EditValue = $arwrk;
            }
            $this->Component_Make->PlaceHolder = RemoveHtml($this->Component_Make->caption());

            // Component_Model
            $this->Component_Model->setupEditAttributes();
            $curVal = trim(strval($this->Component_Model->CurrentValue));
            if ($curVal != "") {
                $this->Component_Model->ViewValue = $this->Component_Model->lookupCacheOption($curVal);
            } else {
                $this->Component_Model->ViewValue = $this->Component_Model->Lookup !== null && is_array($this->Component_Model->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Model->ViewValue !== null) { // Load from cache
                $this->Component_Model->EditValue = array_values($this->Component_Model->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Model`", "=", $this->Component_Model->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Model->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Model->EditValue = $arwrk;
            }
            $this->Component_Model->PlaceHolder = RemoveHtml($this->Component_Model->caption());

            // Component_Serial_Number
            $this->Component_Serial_Number->setupEditAttributes();
            if (!$this->Component_Serial_Number->Raw) {
                $this->Component_Serial_Number->CurrentValue = HtmlDecode($this->Component_Serial_Number->CurrentValue);
            }
            $this->Component_Serial_Number->EditValue = HtmlEncode($this->Component_Serial_Number->CurrentValue);
            $this->Component_Serial_Number->PlaceHolder = RemoveHtml($this->Component_Serial_Number->caption());

            // Component_Display_Size
            $this->Component_Display_Size->setupEditAttributes();
            if (!$this->Component_Display_Size->Raw) {
                $this->Component_Display_Size->CurrentValue = HtmlDecode($this->Component_Display_Size->CurrentValue);
            }
            $this->Component_Display_Size->EditValue = HtmlEncode($this->Component_Display_Size->CurrentValue);
            $this->Component_Display_Size->PlaceHolder = RemoveHtml($this->Component_Display_Size->caption());

            // Component_Keyboard_Layout
            $this->Component_Keyboard_Layout->setupEditAttributes();
            if (!$this->Component_Keyboard_Layout->Raw) {
                $this->Component_Keyboard_Layout->CurrentValue = HtmlDecode($this->Component_Keyboard_Layout->CurrentValue);
            }
            $this->Component_Keyboard_Layout->EditValue = HtmlEncode($this->Component_Keyboard_Layout->CurrentValue);
            $this->Component_Keyboard_Layout->PlaceHolder = RemoveHtml($this->Component_Keyboard_Layout->caption());

            // Component_Type1
            $this->Component_Type1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Type1->CurrentValue));
            if ($curVal != "") {
                $this->Component_Type1->ViewValue = $this->Component_Type1->lookupCacheOption($curVal);
            } else {
                $this->Component_Type1->ViewValue = $this->Component_Type1->Lookup !== null && is_array($this->Component_Type1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Type1->ViewValue !== null) { // Load from cache
                $this->Component_Type1->EditValue = array_values($this->Component_Type1->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Type`", "=", $this->Component_Type1->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Type1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Type1->EditValue = $arwrk;
            }
            $this->Component_Type1->PlaceHolder = RemoveHtml($this->Component_Type1->caption());

            // Component_Category1
            $this->Component_Category1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Category1->CurrentValue));
            if ($curVal != "") {
                $this->Component_Category1->ViewValue = $this->Component_Category1->lookupCacheOption($curVal);
            } else {
                $this->Component_Category1->ViewValue = $this->Component_Category1->Lookup !== null && is_array($this->Component_Category1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Category1->ViewValue !== null) { // Load from cache
                $this->Component_Category1->EditValue = array_values($this->Component_Category1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Category1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Category1->EditValue = $arwrk;
            }
            $this->Component_Category1->PlaceHolder = RemoveHtml($this->Component_Category1->caption());

            // Component_Make1
            $this->Component_Make1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Make1->CurrentValue));
            if ($curVal != "") {
                $this->Component_Make1->ViewValue = $this->Component_Make1->lookupCacheOption($curVal);
            } else {
                $this->Component_Make1->ViewValue = $this->Component_Make1->Lookup !== null && is_array($this->Component_Make1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Make1->ViewValue !== null) { // Load from cache
                $this->Component_Make1->EditValue = array_values($this->Component_Make1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Make1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Make1->EditValue = $arwrk;
            }
            $this->Component_Make1->PlaceHolder = RemoveHtml($this->Component_Make1->caption());

            // Component_Model1
            $this->Component_Model1->setupEditAttributes();
            if (!$this->Component_Model1->Raw) {
                $this->Component_Model1->CurrentValue = HtmlDecode($this->Component_Model1->CurrentValue);
            }
            $this->Component_Model1->EditValue = HtmlEncode($this->Component_Model1->CurrentValue);
            $this->Component_Model1->PlaceHolder = RemoveHtml($this->Component_Model1->caption());

            // Component_Serial_Number1
            $this->Component_Serial_Number1->setupEditAttributes();
            if (!$this->Component_Serial_Number1->Raw) {
                $this->Component_Serial_Number1->CurrentValue = HtmlDecode($this->Component_Serial_Number1->CurrentValue);
            }
            $this->Component_Serial_Number1->EditValue = HtmlEncode($this->Component_Serial_Number1->CurrentValue);
            $this->Component_Serial_Number1->PlaceHolder = RemoveHtml($this->Component_Serial_Number1->caption());

            // Component_Display_Size1
            $this->Component_Display_Size1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Display_Size1->CurrentValue));
            if ($curVal != "") {
                $this->Component_Display_Size1->ViewValue = $this->Component_Display_Size1->lookupCacheOption($curVal);
            } else {
                $this->Component_Display_Size1->ViewValue = $this->Component_Display_Size1->Lookup !== null && is_array($this->Component_Display_Size1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Display_Size1->ViewValue !== null) { // Load from cache
                $this->Component_Display_Size1->EditValue = array_values($this->Component_Display_Size1->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Display Size`", "=", $this->Component_Display_Size1->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Display_Size1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Display_Size1->EditValue = $arwrk;
            }
            $this->Component_Display_Size1->PlaceHolder = RemoveHtml($this->Component_Display_Size1->caption());

            // Component_Keyboard_Layout1
            $this->Component_Keyboard_Layout1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Keyboard_Layout1->CurrentValue));
            if ($curVal != "") {
                $this->Component_Keyboard_Layout1->ViewValue = $this->Component_Keyboard_Layout1->lookupCacheOption($curVal);
            } else {
                $this->Component_Keyboard_Layout1->ViewValue = $this->Component_Keyboard_Layout1->Lookup !== null && is_array($this->Component_Keyboard_Layout1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Keyboard_Layout1->ViewValue !== null) { // Load from cache
                $this->Component_Keyboard_Layout1->EditValue = array_values($this->Component_Keyboard_Layout1->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Keyboard Layout`", "=", $this->Component_Keyboard_Layout1->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Keyboard_Layout1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Keyboard_Layout1->EditValue = $arwrk;
            }
            $this->Component_Keyboard_Layout1->PlaceHolder = RemoveHtml($this->Component_Keyboard_Layout1->caption());

            // Component_Type2
            $this->Component_Type2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Type2->CurrentValue));
            if ($curVal != "") {
                $this->Component_Type2->ViewValue = $this->Component_Type2->lookupCacheOption($curVal);
            } else {
                $this->Component_Type2->ViewValue = $this->Component_Type2->Lookup !== null && is_array($this->Component_Type2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Type2->ViewValue !== null) { // Load from cache
                $this->Component_Type2->EditValue = array_values($this->Component_Type2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Type`", "=", $this->Component_Type2->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Type2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Type2->EditValue = $arwrk;
            }
            $this->Component_Type2->PlaceHolder = RemoveHtml($this->Component_Type2->caption());

            // Component_Category2
            $this->Component_Category2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Category2->CurrentValue));
            if ($curVal != "") {
                $this->Component_Category2->ViewValue = $this->Component_Category2->lookupCacheOption($curVal);
            } else {
                $this->Component_Category2->ViewValue = $this->Component_Category2->Lookup !== null && is_array($this->Component_Category2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Category2->ViewValue !== null) { // Load from cache
                $this->Component_Category2->EditValue = array_values($this->Component_Category2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Category`", "=", $this->Component_Category2->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Category2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Category2->EditValue = $arwrk;
            }
            $this->Component_Category2->PlaceHolder = RemoveHtml($this->Component_Category2->caption());

            // Component_Make2
            $this->Component_Make2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Make2->CurrentValue));
            if ($curVal != "") {
                $this->Component_Make2->ViewValue = $this->Component_Make2->lookupCacheOption($curVal);
            } else {
                $this->Component_Make2->ViewValue = $this->Component_Make2->Lookup !== null && is_array($this->Component_Make2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Make2->ViewValue !== null) { // Load from cache
                $this->Component_Make2->EditValue = array_values($this->Component_Make2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Make`", "=", $this->Component_Make2->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Make2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Make2->EditValue = $arwrk;
            }
            $this->Component_Make2->PlaceHolder = RemoveHtml($this->Component_Make2->caption());

            // Component_Model2
            $this->Component_Model2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Model2->CurrentValue));
            if ($curVal != "") {
                $this->Component_Model2->ViewValue = $this->Component_Model2->lookupCacheOption($curVal);
            } else {
                $this->Component_Model2->ViewValue = $this->Component_Model2->Lookup !== null && is_array($this->Component_Model2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Model2->ViewValue !== null) { // Load from cache
                $this->Component_Model2->EditValue = array_values($this->Component_Model2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Model`", "=", $this->Component_Model2->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Model2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Model2->EditValue = $arwrk;
            }
            $this->Component_Model2->PlaceHolder = RemoveHtml($this->Component_Model2->caption());

            // Component_Serial_Number2
            $this->Component_Serial_Number2->setupEditAttributes();
            if (!$this->Component_Serial_Number2->Raw) {
                $this->Component_Serial_Number2->CurrentValue = HtmlDecode($this->Component_Serial_Number2->CurrentValue);
            }
            $this->Component_Serial_Number2->EditValue = HtmlEncode($this->Component_Serial_Number2->CurrentValue);
            $this->Component_Serial_Number2->PlaceHolder = RemoveHtml($this->Component_Serial_Number2->caption());

            // Component_Display_Size2
            $this->Component_Display_Size2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Display_Size2->CurrentValue));
            if ($curVal != "") {
                $this->Component_Display_Size2->ViewValue = $this->Component_Display_Size2->lookupCacheOption($curVal);
            } else {
                $this->Component_Display_Size2->ViewValue = $this->Component_Display_Size2->Lookup !== null && is_array($this->Component_Display_Size2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Display_Size2->ViewValue !== null) { // Load from cache
                $this->Component_Display_Size2->EditValue = array_values($this->Component_Display_Size2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Display Size`", "=", $this->Component_Display_Size2->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Display_Size2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Display_Size2->EditValue = $arwrk;
            }
            $this->Component_Display_Size2->PlaceHolder = RemoveHtml($this->Component_Display_Size2->caption());

            // Component_Keyboard_Layout2
            $this->Component_Keyboard_Layout2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Keyboard_Layout2->CurrentValue));
            if ($curVal != "") {
                $this->Component_Keyboard_Layout2->ViewValue = $this->Component_Keyboard_Layout2->lookupCacheOption($curVal);
            } else {
                $this->Component_Keyboard_Layout2->ViewValue = $this->Component_Keyboard_Layout2->Lookup !== null && is_array($this->Component_Keyboard_Layout2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Keyboard_Layout2->ViewValue !== null) { // Load from cache
                $this->Component_Keyboard_Layout2->EditValue = array_values($this->Component_Keyboard_Layout2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`Component Keyboard Layout`", "=", $this->Component_Keyboard_Layout2->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->Component_Keyboard_Layout2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Keyboard_Layout2->EditValue = $arwrk;
            }
            $this->Component_Keyboard_Layout2->PlaceHolder = RemoveHtml($this->Component_Keyboard_Layout2->caption());

            // Edit refer script

            // Workstation_Name
            $this->Workstation_Name->HrefValue = "";

            // Workstation_Remark
            $this->Workstation_Remark->HrefValue = "";

            // User_Email
            $this->User_Email->HrefValue = "";

            // User_Name
            $this->User_Name->HrefValue = "";

            // User_Employee_Number
            $this->User_Employee_Number->HrefValue = "";

            // User_Phone_Number
            $this->User_Phone_Number->HrefValue = "";

            // Address_Name
            $this->Address_Name->HrefValue = "";

            // Address_Street
            $this->Address_Street->HrefValue = "";

            // Address_Zipcode
            $this->Address_Zipcode->HrefValue = "";

            // Address_City
            $this->Address_City->HrefValue = "";

            // Address_Country
            $this->Address_Country->HrefValue = "";

            // Component_Type
            $this->Component_Type->HrefValue = "";

            // Component_Category
            $this->Component_Category->HrefValue = "";

            // Component_Make
            $this->Component_Make->HrefValue = "";

            // Component_Model
            $this->Component_Model->HrefValue = "";

            // Component_Serial_Number
            $this->Component_Serial_Number->HrefValue = "";

            // Component_Display_Size
            $this->Component_Display_Size->HrefValue = "";

            // Component_Keyboard_Layout
            $this->Component_Keyboard_Layout->HrefValue = "";

            // Component_Type1
            $this->Component_Type1->HrefValue = "";

            // Component_Category1
            $this->Component_Category1->HrefValue = "";

            // Component_Make1
            $this->Component_Make1->HrefValue = "";

            // Component_Model1
            $this->Component_Model1->HrefValue = "";

            // Component_Serial_Number1
            $this->Component_Serial_Number1->HrefValue = "";

            // Component_Display_Size1
            $this->Component_Display_Size1->HrefValue = "";

            // Component_Keyboard_Layout1
            $this->Component_Keyboard_Layout1->HrefValue = "";

            // Component_Type2
            $this->Component_Type2->HrefValue = "";

            // Component_Category2
            $this->Component_Category2->HrefValue = "";

            // Component_Make2
            $this->Component_Make2->HrefValue = "";

            // Component_Model2
            $this->Component_Model2->HrefValue = "";

            // Component_Serial_Number2
            $this->Component_Serial_Number2->HrefValue = "";

            // Component_Display_Size2
            $this->Component_Display_Size2->HrefValue = "";

            // Component_Keyboard_Layout2
            $this->Component_Keyboard_Layout2->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;
        $updateCnt = 0;
        if ($this->Workstation_Name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Workstation_Remark->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->User_Email->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->User_Name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->User_Employee_Number->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->User_Phone_Number->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Address_Name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Address_Street->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Address_Zipcode->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Address_City->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Address_Country->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Type->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Category->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Make->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Model->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Serial_Number->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Display_Size->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Keyboard_Layout->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Type1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Category1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Make1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Model1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Serial_Number1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Display_Size1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Keyboard_Layout1->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Type2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Category2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Make2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Model2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Serial_Number2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Display_Size2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Component_Keyboard_Layout2->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($updateCnt == 0) {
            return false;
        }

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->Workstation_Name->Required) {
            if ($this->Workstation_Name->MultiUpdate != "" && !$this->Workstation_Name->IsDetailKey && EmptyValue($this->Workstation_Name->FormValue)) {
                $this->Workstation_Name->addErrorMessage(str_replace("%s", $this->Workstation_Name->caption(), $this->Workstation_Name->RequiredErrorMessage));
            }
        }
        if ($this->Workstation_Remark->Required) {
            if ($this->Workstation_Remark->MultiUpdate != "" && !$this->Workstation_Remark->IsDetailKey && EmptyValue($this->Workstation_Remark->FormValue)) {
                $this->Workstation_Remark->addErrorMessage(str_replace("%s", $this->Workstation_Remark->caption(), $this->Workstation_Remark->RequiredErrorMessage));
            }
        }
        if ($this->User_Email->Required) {
            if ($this->User_Email->MultiUpdate != "" && !$this->User_Email->IsDetailKey && EmptyValue($this->User_Email->FormValue)) {
                $this->User_Email->addErrorMessage(str_replace("%s", $this->User_Email->caption(), $this->User_Email->RequiredErrorMessage));
            }
        }
        if ($this->User_Name->Required) {
            if ($this->User_Name->MultiUpdate != "" && !$this->User_Name->IsDetailKey && EmptyValue($this->User_Name->FormValue)) {
                $this->User_Name->addErrorMessage(str_replace("%s", $this->User_Name->caption(), $this->User_Name->RequiredErrorMessage));
            }
        }
        if ($this->User_Employee_Number->Required) {
            if ($this->User_Employee_Number->MultiUpdate != "" && !$this->User_Employee_Number->IsDetailKey && EmptyValue($this->User_Employee_Number->FormValue)) {
                $this->User_Employee_Number->addErrorMessage(str_replace("%s", $this->User_Employee_Number->caption(), $this->User_Employee_Number->RequiredErrorMessage));
            }
        }
        if ($this->User_Phone_Number->Required) {
            if ($this->User_Phone_Number->MultiUpdate != "" && !$this->User_Phone_Number->IsDetailKey && EmptyValue($this->User_Phone_Number->FormValue)) {
                $this->User_Phone_Number->addErrorMessage(str_replace("%s", $this->User_Phone_Number->caption(), $this->User_Phone_Number->RequiredErrorMessage));
            }
        }
        if ($this->Address_Name->Required) {
            if ($this->Address_Name->MultiUpdate != "" && !$this->Address_Name->IsDetailKey && EmptyValue($this->Address_Name->FormValue)) {
                $this->Address_Name->addErrorMessage(str_replace("%s", $this->Address_Name->caption(), $this->Address_Name->RequiredErrorMessage));
            }
        }
        if ($this->Address_Street->Required) {
            if ($this->Address_Street->MultiUpdate != "" && !$this->Address_Street->IsDetailKey && EmptyValue($this->Address_Street->FormValue)) {
                $this->Address_Street->addErrorMessage(str_replace("%s", $this->Address_Street->caption(), $this->Address_Street->RequiredErrorMessage));
            }
        }
        if ($this->Address_Zipcode->Required) {
            if ($this->Address_Zipcode->MultiUpdate != "" && !$this->Address_Zipcode->IsDetailKey && EmptyValue($this->Address_Zipcode->FormValue)) {
                $this->Address_Zipcode->addErrorMessage(str_replace("%s", $this->Address_Zipcode->caption(), $this->Address_Zipcode->RequiredErrorMessage));
            }
        }
        if ($this->Address_City->Required) {
            if ($this->Address_City->MultiUpdate != "" && !$this->Address_City->IsDetailKey && EmptyValue($this->Address_City->FormValue)) {
                $this->Address_City->addErrorMessage(str_replace("%s", $this->Address_City->caption(), $this->Address_City->RequiredErrorMessage));
            }
        }
        if ($this->Address_Country->Required) {
            if ($this->Address_Country->MultiUpdate != "" && !$this->Address_Country->IsDetailKey && EmptyValue($this->Address_Country->FormValue)) {
                $this->Address_Country->addErrorMessage(str_replace("%s", $this->Address_Country->caption(), $this->Address_Country->RequiredErrorMessage));
            }
        }
        if ($this->Component_Type->Required) {
            if ($this->Component_Type->MultiUpdate != "" && !$this->Component_Type->IsDetailKey && EmptyValue($this->Component_Type->FormValue)) {
                $this->Component_Type->addErrorMessage(str_replace("%s", $this->Component_Type->caption(), $this->Component_Type->RequiredErrorMessage));
            }
        }
        if ($this->Component_Category->Required) {
            if ($this->Component_Category->MultiUpdate != "" && !$this->Component_Category->IsDetailKey && EmptyValue($this->Component_Category->FormValue)) {
                $this->Component_Category->addErrorMessage(str_replace("%s", $this->Component_Category->caption(), $this->Component_Category->RequiredErrorMessage));
            }
        }
        if ($this->Component_Make->Required) {
            if ($this->Component_Make->MultiUpdate != "" && !$this->Component_Make->IsDetailKey && EmptyValue($this->Component_Make->FormValue)) {
                $this->Component_Make->addErrorMessage(str_replace("%s", $this->Component_Make->caption(), $this->Component_Make->RequiredErrorMessage));
            }
        }
        if ($this->Component_Model->Required) {
            if ($this->Component_Model->MultiUpdate != "" && !$this->Component_Model->IsDetailKey && EmptyValue($this->Component_Model->FormValue)) {
                $this->Component_Model->addErrorMessage(str_replace("%s", $this->Component_Model->caption(), $this->Component_Model->RequiredErrorMessage));
            }
        }
        if ($this->Component_Serial_Number->Required) {
            if ($this->Component_Serial_Number->MultiUpdate != "" && !$this->Component_Serial_Number->IsDetailKey && EmptyValue($this->Component_Serial_Number->FormValue)) {
                $this->Component_Serial_Number->addErrorMessage(str_replace("%s", $this->Component_Serial_Number->caption(), $this->Component_Serial_Number->RequiredErrorMessage));
            }
        }
        if ($this->Component_Display_Size->Required) {
            if ($this->Component_Display_Size->MultiUpdate != "" && !$this->Component_Display_Size->IsDetailKey && EmptyValue($this->Component_Display_Size->FormValue)) {
                $this->Component_Display_Size->addErrorMessage(str_replace("%s", $this->Component_Display_Size->caption(), $this->Component_Display_Size->RequiredErrorMessage));
            }
        }
        if ($this->Component_Keyboard_Layout->Required) {
            if ($this->Component_Keyboard_Layout->MultiUpdate != "" && !$this->Component_Keyboard_Layout->IsDetailKey && EmptyValue($this->Component_Keyboard_Layout->FormValue)) {
                $this->Component_Keyboard_Layout->addErrorMessage(str_replace("%s", $this->Component_Keyboard_Layout->caption(), $this->Component_Keyboard_Layout->RequiredErrorMessage));
            }
        }
        if ($this->Component_Type1->Required) {
            if ($this->Component_Type1->MultiUpdate != "" && !$this->Component_Type1->IsDetailKey && EmptyValue($this->Component_Type1->FormValue)) {
                $this->Component_Type1->addErrorMessage(str_replace("%s", $this->Component_Type1->caption(), $this->Component_Type1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Category1->Required) {
            if ($this->Component_Category1->MultiUpdate != "" && !$this->Component_Category1->IsDetailKey && EmptyValue($this->Component_Category1->FormValue)) {
                $this->Component_Category1->addErrorMessage(str_replace("%s", $this->Component_Category1->caption(), $this->Component_Category1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Make1->Required) {
            if ($this->Component_Make1->MultiUpdate != "" && !$this->Component_Make1->IsDetailKey && EmptyValue($this->Component_Make1->FormValue)) {
                $this->Component_Make1->addErrorMessage(str_replace("%s", $this->Component_Make1->caption(), $this->Component_Make1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Model1->Required) {
            if ($this->Component_Model1->MultiUpdate != "" && !$this->Component_Model1->IsDetailKey && EmptyValue($this->Component_Model1->FormValue)) {
                $this->Component_Model1->addErrorMessage(str_replace("%s", $this->Component_Model1->caption(), $this->Component_Model1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Serial_Number1->Required) {
            if ($this->Component_Serial_Number1->MultiUpdate != "" && !$this->Component_Serial_Number1->IsDetailKey && EmptyValue($this->Component_Serial_Number1->FormValue)) {
                $this->Component_Serial_Number1->addErrorMessage(str_replace("%s", $this->Component_Serial_Number1->caption(), $this->Component_Serial_Number1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Display_Size1->Required) {
            if ($this->Component_Display_Size1->MultiUpdate != "" && !$this->Component_Display_Size1->IsDetailKey && EmptyValue($this->Component_Display_Size1->FormValue)) {
                $this->Component_Display_Size1->addErrorMessage(str_replace("%s", $this->Component_Display_Size1->caption(), $this->Component_Display_Size1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Keyboard_Layout1->Required) {
            if ($this->Component_Keyboard_Layout1->MultiUpdate != "" && !$this->Component_Keyboard_Layout1->IsDetailKey && EmptyValue($this->Component_Keyboard_Layout1->FormValue)) {
                $this->Component_Keyboard_Layout1->addErrorMessage(str_replace("%s", $this->Component_Keyboard_Layout1->caption(), $this->Component_Keyboard_Layout1->RequiredErrorMessage));
            }
        }
        if ($this->Component_Type2->Required) {
            if ($this->Component_Type2->MultiUpdate != "" && !$this->Component_Type2->IsDetailKey && EmptyValue($this->Component_Type2->FormValue)) {
                $this->Component_Type2->addErrorMessage(str_replace("%s", $this->Component_Type2->caption(), $this->Component_Type2->RequiredErrorMessage));
            }
        }
        if ($this->Component_Category2->Required) {
            if ($this->Component_Category2->MultiUpdate != "" && !$this->Component_Category2->IsDetailKey && EmptyValue($this->Component_Category2->FormValue)) {
                $this->Component_Category2->addErrorMessage(str_replace("%s", $this->Component_Category2->caption(), $this->Component_Category2->RequiredErrorMessage));
            }
        }
        if ($this->Component_Make2->Required) {
            if ($this->Component_Make2->MultiUpdate != "" && !$this->Component_Make2->IsDetailKey && EmptyValue($this->Component_Make2->FormValue)) {
                $this->Component_Make2->addErrorMessage(str_replace("%s", $this->Component_Make2->caption(), $this->Component_Make2->RequiredErrorMessage));
            }
        }
        if ($this->Component_Model2->Required) {
            if ($this->Component_Model2->MultiUpdate != "" && !$this->Component_Model2->IsDetailKey && EmptyValue($this->Component_Model2->FormValue)) {
                $this->Component_Model2->addErrorMessage(str_replace("%s", $this->Component_Model2->caption(), $this->Component_Model2->RequiredErrorMessage));
            }
        }
        if ($this->Component_Serial_Number2->Required) {
            if ($this->Component_Serial_Number2->MultiUpdate != "" && !$this->Component_Serial_Number2->IsDetailKey && EmptyValue($this->Component_Serial_Number2->FormValue)) {
                $this->Component_Serial_Number2->addErrorMessage(str_replace("%s", $this->Component_Serial_Number2->caption(), $this->Component_Serial_Number2->RequiredErrorMessage));
            }
        }
        if ($this->Component_Display_Size2->Required) {
            if ($this->Component_Display_Size2->MultiUpdate != "" && !$this->Component_Display_Size2->IsDetailKey && EmptyValue($this->Component_Display_Size2->FormValue)) {
                $this->Component_Display_Size2->addErrorMessage(str_replace("%s", $this->Component_Display_Size2->caption(), $this->Component_Display_Size2->RequiredErrorMessage));
            }
        }
        if ($this->Component_Keyboard_Layout2->Required) {
            if ($this->Component_Keyboard_Layout2->MultiUpdate != "" && !$this->Component_Keyboard_Layout2->IsDetailKey && EmptyValue($this->Component_Keyboard_Layout2->FormValue)) {
                $this->Component_Keyboard_Layout2->addErrorMessage(str_replace("%s", $this->Component_Keyboard_Layout2->caption(), $this->Component_Keyboard_Layout2->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

        // Set new row
        $rsnew = [];

        // Workstation_Name
        $this->Workstation_Name->setDbValueDef($rsnew, $this->Workstation_Name->CurrentValue, null, $this->Workstation_Name->ReadOnly || $this->Workstation_Name->MultiUpdate != "1");

        // Workstation_Remark
        $this->Workstation_Remark->setDbValueDef($rsnew, $this->Workstation_Remark->CurrentValue, null, $this->Workstation_Remark->ReadOnly || $this->Workstation_Remark->MultiUpdate != "1");

        // User_Email
        $this->User_Email->setDbValueDef($rsnew, $this->User_Email->CurrentValue, null, $this->User_Email->ReadOnly || $this->User_Email->MultiUpdate != "1");

        // User_Name
        $this->User_Name->setDbValueDef($rsnew, $this->User_Name->CurrentValue, null, $this->User_Name->ReadOnly || $this->User_Name->MultiUpdate != "1");

        // User_Employee_Number
        $this->User_Employee_Number->setDbValueDef($rsnew, $this->User_Employee_Number->CurrentValue, null, $this->User_Employee_Number->ReadOnly || $this->User_Employee_Number->MultiUpdate != "1");

        // User_Phone_Number
        $this->User_Phone_Number->setDbValueDef($rsnew, $this->User_Phone_Number->CurrentValue, null, $this->User_Phone_Number->ReadOnly || $this->User_Phone_Number->MultiUpdate != "1");

        // Address_Name
        $this->Address_Name->setDbValueDef($rsnew, $this->Address_Name->CurrentValue, null, $this->Address_Name->ReadOnly || $this->Address_Name->MultiUpdate != "1");

        // Address_Street
        $this->Address_Street->setDbValueDef($rsnew, $this->Address_Street->CurrentValue, null, $this->Address_Street->ReadOnly || $this->Address_Street->MultiUpdate != "1");

        // Address_Zipcode
        $this->Address_Zipcode->setDbValueDef($rsnew, $this->Address_Zipcode->CurrentValue, null, $this->Address_Zipcode->ReadOnly || $this->Address_Zipcode->MultiUpdate != "1");

        // Address_City
        $this->Address_City->setDbValueDef($rsnew, $this->Address_City->CurrentValue, null, $this->Address_City->ReadOnly || $this->Address_City->MultiUpdate != "1");

        // Address_Country
        $this->Address_Country->setDbValueDef($rsnew, $this->Address_Country->CurrentValue, null, $this->Address_Country->ReadOnly || $this->Address_Country->MultiUpdate != "1");

        // Component_Type
        $this->Component_Type->setDbValueDef($rsnew, $this->Component_Type->CurrentValue, null, $this->Component_Type->ReadOnly || $this->Component_Type->MultiUpdate != "1");

        // Component_Category
        $this->Component_Category->setDbValueDef($rsnew, $this->Component_Category->CurrentValue, null, $this->Component_Category->ReadOnly || $this->Component_Category->MultiUpdate != "1");

        // Component_Make
        $this->Component_Make->setDbValueDef($rsnew, $this->Component_Make->CurrentValue, null, $this->Component_Make->ReadOnly || $this->Component_Make->MultiUpdate != "1");

        // Component_Model
        $this->Component_Model->setDbValueDef($rsnew, $this->Component_Model->CurrentValue, null, $this->Component_Model->ReadOnly || $this->Component_Model->MultiUpdate != "1");

        // Component_Serial_Number
        $this->Component_Serial_Number->setDbValueDef($rsnew, $this->Component_Serial_Number->CurrentValue, null, $this->Component_Serial_Number->ReadOnly || $this->Component_Serial_Number->MultiUpdate != "1");

        // Component_Display_Size
        $this->Component_Display_Size->setDbValueDef($rsnew, $this->Component_Display_Size->CurrentValue, null, $this->Component_Display_Size->ReadOnly || $this->Component_Display_Size->MultiUpdate != "1");

        // Component_Keyboard_Layout
        $this->Component_Keyboard_Layout->setDbValueDef($rsnew, $this->Component_Keyboard_Layout->CurrentValue, null, $this->Component_Keyboard_Layout->ReadOnly || $this->Component_Keyboard_Layout->MultiUpdate != "1");

        // Component_Type1
        $this->Component_Type1->setDbValueDef($rsnew, $this->Component_Type1->CurrentValue, null, $this->Component_Type1->ReadOnly || $this->Component_Type1->MultiUpdate != "1");

        // Component_Category1
        $this->Component_Category1->setDbValueDef($rsnew, $this->Component_Category1->CurrentValue, null, $this->Component_Category1->ReadOnly || $this->Component_Category1->MultiUpdate != "1");

        // Component_Make1
        $this->Component_Make1->setDbValueDef($rsnew, $this->Component_Make1->CurrentValue, null, $this->Component_Make1->ReadOnly || $this->Component_Make1->MultiUpdate != "1");

        // Component_Model1
        $this->Component_Model1->setDbValueDef($rsnew, $this->Component_Model1->CurrentValue, null, $this->Component_Model1->ReadOnly || $this->Component_Model1->MultiUpdate != "1");

        // Component_Serial_Number1
        $this->Component_Serial_Number1->setDbValueDef($rsnew, $this->Component_Serial_Number1->CurrentValue, null, $this->Component_Serial_Number1->ReadOnly || $this->Component_Serial_Number1->MultiUpdate != "1");

        // Component_Display_Size1
        $this->Component_Display_Size1->setDbValueDef($rsnew, $this->Component_Display_Size1->CurrentValue, null, $this->Component_Display_Size1->ReadOnly || $this->Component_Display_Size1->MultiUpdate != "1");

        // Component_Keyboard_Layout1
        $this->Component_Keyboard_Layout1->setDbValueDef($rsnew, $this->Component_Keyboard_Layout1->CurrentValue, null, $this->Component_Keyboard_Layout1->ReadOnly || $this->Component_Keyboard_Layout1->MultiUpdate != "1");

        // Component_Type2
        $this->Component_Type2->setDbValueDef($rsnew, $this->Component_Type2->CurrentValue, null, $this->Component_Type2->ReadOnly || $this->Component_Type2->MultiUpdate != "1");

        // Component_Category2
        $this->Component_Category2->setDbValueDef($rsnew, $this->Component_Category2->CurrentValue, null, $this->Component_Category2->ReadOnly || $this->Component_Category2->MultiUpdate != "1");

        // Component_Make2
        $this->Component_Make2->setDbValueDef($rsnew, $this->Component_Make2->CurrentValue, null, $this->Component_Make2->ReadOnly || $this->Component_Make2->MultiUpdate != "1");

        // Component_Model2
        $this->Component_Model2->setDbValueDef($rsnew, $this->Component_Model2->CurrentValue, null, $this->Component_Model2->ReadOnly || $this->Component_Model2->MultiUpdate != "1");

        // Component_Serial_Number2
        $this->Component_Serial_Number2->setDbValueDef($rsnew, $this->Component_Serial_Number2->CurrentValue, null, $this->Component_Serial_Number2->ReadOnly || $this->Component_Serial_Number2->MultiUpdate != "1");

        // Component_Display_Size2
        $this->Component_Display_Size2->setDbValueDef($rsnew, $this->Component_Display_Size2->CurrentValue, null, $this->Component_Display_Size2->ReadOnly || $this->Component_Display_Size2->MultiUpdate != "1");

        // Component_Keyboard_Layout2
        $this->Component_Keyboard_Layout2->setDbValueDef($rsnew, $this->Component_Keyboard_Layout2->CurrentValue, null, $this->Component_Keyboard_Layout2->ReadOnly || $this->Component_Keyboard_Layout2->MultiUpdate != "1");

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }
        return $editRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("workstationlist"), "", $this->TableVar, true);
        $pageId = "update";
        $Breadcrumb->add("update", $pageId, $url);
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
