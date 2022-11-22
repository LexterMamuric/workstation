<?php

namespace WorkStationDB\project3;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class WorkstationList extends Workstation
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "WorkstationList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fworkstationlist";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "workstationlist";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

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
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'workstation';
        $this->TableName = 'workstation';

        // Table CSS class
        $this->TableClass = "table table-bordered table-hover table-sm ew-table";

        // CSS class name as context
        $this->ContextClass = CheckClassName($this->TableVar);
        AppendClass($this->TableGridClass, $this->ContextClass);

        // Fixed header table
        if (!$this->UseCustomTemplate) {
            $this->setFixedHeaderTable(Config("USE_FIXED_HEADER_TABLE"), Config("FIXED_HEADER_TABLE_HEIGHT"));
        }

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (workstation)
        if (!isset($GLOBALS["workstation"]) || get_class($GLOBALS["workstation"]) == PROJECT_NAMESPACE . "workstation") {
            $GLOBALS["workstation"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "workstationadd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "workstationdelete";
        $this->MultiUpdateUrl = "workstationupdate";

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

        // List options
        $this->ListOptions = new ListOptions(["Tag" => "td", "TableVar" => $this->TableVar]);

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Import options
        $this->ImportOptions = new ListOptions(["TagClassName" => "ew-import-option"]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions([
            "TagClassName" => "ew-add-edit-option",
            "UseDropDownButton" => false,
            "DropDownButtonPhrase" => $Language->phrase("ButtonAddEdit"),
            "UseButtonGroup" => true
        ]);

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(["TagClassName" => "ew-detail-option"]);
        // Actions
        $this->OtherOptions["action"] = new ListOptions(["TagClassName" => "ew-action-option"]);

        // Column visibility
        $this->OtherOptions["column"] = new ListOptions([
            "TableVar" => $this->TableVar,
            "TagClassName" => "ew-column-option",
            "ButtonGroupClass" => "ew-column-dropdown",
            "UseDropDownButton" => true,
            "DropDownButtonPhrase" => $Language->phrase("Columns"),
            "DropDownAutoClose" => "outside",
            "UseButtonGroup" => false
        ]);

        // Filter options
        $this->FilterOptions = new ListOptions(["TagClassName" => "ew-filter-option"]);

        // List actions
        $this->ListActions = new ListActions();
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
                        if ($fld->DataType == DATATYPE_MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $InlineRowCount = 0;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $UserAction; // User action
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $TopContentClass = "ew-top";
    public $MiddleContentClass = "ew-middle";
    public $BottomContentClass = "ew-bottom";
    public $PageAction;
    public $RecKeys = [];
    public $IsModal = false;
    protected $FilterForModalActions = "";
    private $UseInfiniteScroll = false;

    /**
     * Load recordset from filter
     *
     * @return void
     */
    public function loadRecordsetFromFilter($filter)
    {
        // Set up list options
        $this->setupListOptions();

        // Search options
        $this->setupSearchOptions();

        // Load recordset
        $this->TotalRecords = $this->loadRecordCount($filter);
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords;
        $this->CurrentFilter = $filter;
        $this->Recordset = $this->loadRecordset();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);
    }

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $DashboardReport;

        // Multi column button position
        $this->MultiColumnListOptionsPosition = Config("MULTI_COLUMN_LIST_OPTIONS_POSITION");

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportType = $this->Export; // Get export parameter, used in header
        if ($ExportType != "") {
            global $SkipHeaderFooter;
            $SkipHeaderFooter = true;
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();

        // Setup export options
        $this->setupExportOptions();

        // Setup import options
        $this->setupImportOptions();
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
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

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "fworkstationgrid";
        }

        // Set up page action
        $this->PageAction = CurrentPageUrl(false);

        // Set up infinite scroll
        $this->UseInfiniteScroll = ConvertToBool(Param("infinitescroll"));

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = ""; // Filter
        $query = ""; // Query builder

        // Get command
        $this->Command = strtolower(Get("cmd", ""));

        // Process list action first
        if ($this->processListAction()) { // Ajax request
            $this->terminate();
            return;
        }

        // Set up records per page
        $this->setupDisplayRecords();

        // Handle reset command
        $this->resetCmd();

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Process import
        if ($this->isImport()) {
            $this->import(Param(Config("API_FILE_TOKEN_NAME")), ConvertToBool(Param("rollback")));
            $this->terminate();
            return;
        }

        // Hide list options
        if ($this->isExport()) {
            $this->ListOptions->hideAllOptions(["sequence"]);
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        } elseif ($this->isGridAdd() || $this->isGridEdit() || $this->isMultiEdit() || $this->isConfirm()) {
            $this->ListOptions->hideAllOptions();
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        }

        // Hide options
        if ($this->isExport() || !(EmptyValue($this->CurrentAction) || $this->isSearch())) {
            $this->ExportOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
            $this->ImportOptions->hideAllOptions();
        }

        // Hide other options
        if ($this->isExport()) {
            $this->OtherOptions->hideAllOptions();
        }

        // Get default search criteria
        AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));
        AddFilter($this->DefaultSearchWhere, $this->advancedSearchWhere(true));

        // Get basic search values
        $this->loadBasicSearchValues();

        // Get and validate search values for advanced search
        if (EmptyValue($this->UserAction)) { // Skip if user action
            $this->loadSearchValues();
        }

        // Process filter list
        if ($this->processFilterList()) {
            $this->terminate();
            return;
        }
        if (!$this->validateSearch()) {
            // Nothing to do
        }

        // Restore search parms from Session if not searching / reset / export
        if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
            $this->restoreSearchParms();
        }

        // Call Recordset SearchValidated event
        $this->recordsetSearchValidated();

        // Set up sorting order
        $this->setupSortOrder();

        // Get basic search criteria
        if (!$this->hasInvalidFields()) {
            $srchBasic = $this->basicSearchWhere();
        }

        // Get advanced search criteria
        if (!$this->hasInvalidFields()) {
            $srchAdvanced = $this->advancedSearchWhere();
        }

        // Get query builder criteria
        $query = $this->queryBuilderWhere();

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms()) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere();
            }

            // Load advanced search from default
            if ($this->loadAdvancedSearchDefault()) {
                $srchAdvanced = $this->advancedSearchWhere();
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Build search criteria
        if ($query) {
            AddFilter($this->SearchWhere, $query);
        } else {
            AddFilter($this->SearchWhere, $srchAdvanced);
            AddFilter($this->SearchWhere, $srchBasic);
        }

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json" && !$query) {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        $filter = "";
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        $this->Filter = $filter;
        if ($this->isGridAdd()) {
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } elseif (($this->isEdit() || $this->isCopy() || $this->isInlineInserted() || $this->isInlineUpdated()) && $this->UseInfiniteScroll) { // Get current record only
            $this->CurrentFilter = $this->isInlineUpdated() ? $this->getRecordFilter() : $this->getFilterFromRecordKeys();
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } elseif (
            $this->UseInfiniteScroll && $this->isGridInserted() ||
            $this->UseInfiniteScroll && ($this->isGridEdit() || $this->isGridUpdated()) ||
            $this->isMultiEdit() ||
            $this->UseInfiniteScroll && $this->isMultiUpdated()
        ) { // Get current records only
            $this->CurrentFilter = $this->FilterForModalActions; // Restore filter
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if ((EmptyValue($this->CurrentAction) || $this->isSearch()) && $this->TotalRecords == 0) {
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }
        }

        // Set up list action columns
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Allow) {
                if ($listaction->Select == ACTION_MULTIPLE) { // Show checkbox column if multiple action
                    $this->ListOptions["checkbox"]->Visible = true;
                } elseif ($listaction->Select == ACTION_SINGLE) { // Show list action column
                        $this->ListOptions["listactions"]->Visible = true; // Set visible if any list action is allowed
                }
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            if ($query) { // Hide search panel if using QueryBuilder
                RemoveClass($this->SearchPanelClass, "show");
            } else {
                AppendClass($this->SearchPanelClass, "show");
            }
        }

        // API list action
        if (IsApi()) {
            if (Route(0) == Config("API_LIST_ACTION")) {
                if (!$this->isExport()) {
                    $rows = $this->getRecordsFromRecordset($this->Recordset);
                    $this->Recordset->close();
                    WriteJson(["success" => true, "action" => Config("API_LIST_ACTION"), $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
                    $this->terminate(true);
                }
                return;
            } elseif ($this->getFailureMessage() != "") {
                WriteJson(["error" => $this->getFailureMessage()]);
                $this->clearFailureMessage();
                $this->terminate(true);
                return;
            }
        }

        // Render other options
        $this->renderOtherOptions();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set ReturnUrl in header if necessary
        if ($returnUrl = Container("flash")->getFirstMessage("Return-Url")) {
            AddHeader("Return-Url", GetUrl($returnUrl));
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

    // Get page number
    public function getPageNumber()
    {
        return ($this->DisplayRecords > 0 && $this->StartRecord > 0) ? ceil($this->StartRecord / $this->DisplayRecords) : 1;
    }

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";
        $filterList = Concat($filterList, $this->Workstation_Name->AdvancedSearch->toJson(), ","); // Field Workstation_Name
        $filterList = Concat($filterList, $this->Workstation_Remark->AdvancedSearch->toJson(), ","); // Field Workstation_Remark
        $filterList = Concat($filterList, $this->User_Email->AdvancedSearch->toJson(), ","); // Field User_Email
        $filterList = Concat($filterList, $this->User_Name->AdvancedSearch->toJson(), ","); // Field User_Name
        $filterList = Concat($filterList, $this->User_Employee_Number->AdvancedSearch->toJson(), ","); // Field User_Employee_Number
        $filterList = Concat($filterList, $this->User_Phone_Number->AdvancedSearch->toJson(), ","); // Field User_Phone_Number
        $filterList = Concat($filterList, $this->Address_Name->AdvancedSearch->toJson(), ","); // Field Address_Name
        $filterList = Concat($filterList, $this->Address_Street->AdvancedSearch->toJson(), ","); // Field Address_Street
        $filterList = Concat($filterList, $this->Address_Zipcode->AdvancedSearch->toJson(), ","); // Field Address_Zipcode
        $filterList = Concat($filterList, $this->Address_City->AdvancedSearch->toJson(), ","); // Field Address_City
        $filterList = Concat($filterList, $this->Address_Country->AdvancedSearch->toJson(), ","); // Field Address_Country
        $filterList = Concat($filterList, $this->Component_Type->AdvancedSearch->toJson(), ","); // Field Component_Type
        $filterList = Concat($filterList, $this->Component_Category->AdvancedSearch->toJson(), ","); // Field Component_Category
        $filterList = Concat($filterList, $this->Component_Make->AdvancedSearch->toJson(), ","); // Field Component_Make
        $filterList = Concat($filterList, $this->Component_Model->AdvancedSearch->toJson(), ","); // Field Component_Model
        $filterList = Concat($filterList, $this->Component_Serial_Number->AdvancedSearch->toJson(), ","); // Field Component_Serial_Number
        $filterList = Concat($filterList, $this->Component_Display_Size->AdvancedSearch->toJson(), ","); // Field Component_Display_Size
        $filterList = Concat($filterList, $this->Component_Keyboard_Layout->AdvancedSearch->toJson(), ","); // Field Component_Keyboard_Layout
        $filterList = Concat($filterList, $this->Component_Type1->AdvancedSearch->toJson(), ","); // Field Component_Type1
        $filterList = Concat($filterList, $this->Component_Category1->AdvancedSearch->toJson(), ","); // Field Component_Category1
        $filterList = Concat($filterList, $this->Component_Make1->AdvancedSearch->toJson(), ","); // Field Component_Make1
        $filterList = Concat($filterList, $this->Component_Model1->AdvancedSearch->toJson(), ","); // Field Component_Model1
        $filterList = Concat($filterList, $this->Component_Serial_Number1->AdvancedSearch->toJson(), ","); // Field Component_Serial_Number1
        $filterList = Concat($filterList, $this->Component_Display_Size1->AdvancedSearch->toJson(), ","); // Field Component_Display_Size1
        $filterList = Concat($filterList, $this->Component_Keyboard_Layout1->AdvancedSearch->toJson(), ","); // Field Component_Keyboard_Layout1
        $filterList = Concat($filterList, $this->Component_Type2->AdvancedSearch->toJson(), ","); // Field Component_Type2
        $filterList = Concat($filterList, $this->Component_Category2->AdvancedSearch->toJson(), ","); // Field Component_Category2
        $filterList = Concat($filterList, $this->Component_Make2->AdvancedSearch->toJson(), ","); // Field Component_Make2
        $filterList = Concat($filterList, $this->Component_Model2->AdvancedSearch->toJson(), ","); // Field Component_Model2
        $filterList = Concat($filterList, $this->Component_Serial_Number2->AdvancedSearch->toJson(), ","); // Field Component_Serial_Number2
        $filterList = Concat($filterList, $this->Component_Display_Size2->AdvancedSearch->toJson(), ","); // Field Component_Display_Size2
        $filterList = Concat($filterList, $this->Component_Keyboard_Layout2->AdvancedSearch->toJson(), ","); // Field Component_Keyboard_Layout2
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        global $UserProfile;
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            $UserProfile->setSearchFilters(CurrentUserName(), "fworkstationsrch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field Workstation_Name
        $this->Workstation_Name->AdvancedSearch->SearchValue = @$filter["x_Workstation_Name"];
        $this->Workstation_Name->AdvancedSearch->SearchOperator = @$filter["z_Workstation_Name"];
        $this->Workstation_Name->AdvancedSearch->SearchCondition = @$filter["v_Workstation_Name"];
        $this->Workstation_Name->AdvancedSearch->SearchValue2 = @$filter["y_Workstation_Name"];
        $this->Workstation_Name->AdvancedSearch->SearchOperator2 = @$filter["w_Workstation_Name"];
        $this->Workstation_Name->AdvancedSearch->save();

        // Field Workstation_Remark
        $this->Workstation_Remark->AdvancedSearch->SearchValue = @$filter["x_Workstation_Remark"];
        $this->Workstation_Remark->AdvancedSearch->SearchOperator = @$filter["z_Workstation_Remark"];
        $this->Workstation_Remark->AdvancedSearch->SearchCondition = @$filter["v_Workstation_Remark"];
        $this->Workstation_Remark->AdvancedSearch->SearchValue2 = @$filter["y_Workstation_Remark"];
        $this->Workstation_Remark->AdvancedSearch->SearchOperator2 = @$filter["w_Workstation_Remark"];
        $this->Workstation_Remark->AdvancedSearch->save();

        // Field User_Email
        $this->User_Email->AdvancedSearch->SearchValue = @$filter["x_User_Email"];
        $this->User_Email->AdvancedSearch->SearchOperator = @$filter["z_User_Email"];
        $this->User_Email->AdvancedSearch->SearchCondition = @$filter["v_User_Email"];
        $this->User_Email->AdvancedSearch->SearchValue2 = @$filter["y_User_Email"];
        $this->User_Email->AdvancedSearch->SearchOperator2 = @$filter["w_User_Email"];
        $this->User_Email->AdvancedSearch->save();

        // Field User_Name
        $this->User_Name->AdvancedSearch->SearchValue = @$filter["x_User_Name"];
        $this->User_Name->AdvancedSearch->SearchOperator = @$filter["z_User_Name"];
        $this->User_Name->AdvancedSearch->SearchCondition = @$filter["v_User_Name"];
        $this->User_Name->AdvancedSearch->SearchValue2 = @$filter["y_User_Name"];
        $this->User_Name->AdvancedSearch->SearchOperator2 = @$filter["w_User_Name"];
        $this->User_Name->AdvancedSearch->save();

        // Field User_Employee_Number
        $this->User_Employee_Number->AdvancedSearch->SearchValue = @$filter["x_User_Employee_Number"];
        $this->User_Employee_Number->AdvancedSearch->SearchOperator = @$filter["z_User_Employee_Number"];
        $this->User_Employee_Number->AdvancedSearch->SearchCondition = @$filter["v_User_Employee_Number"];
        $this->User_Employee_Number->AdvancedSearch->SearchValue2 = @$filter["y_User_Employee_Number"];
        $this->User_Employee_Number->AdvancedSearch->SearchOperator2 = @$filter["w_User_Employee_Number"];
        $this->User_Employee_Number->AdvancedSearch->save();

        // Field User_Phone_Number
        $this->User_Phone_Number->AdvancedSearch->SearchValue = @$filter["x_User_Phone_Number"];
        $this->User_Phone_Number->AdvancedSearch->SearchOperator = @$filter["z_User_Phone_Number"];
        $this->User_Phone_Number->AdvancedSearch->SearchCondition = @$filter["v_User_Phone_Number"];
        $this->User_Phone_Number->AdvancedSearch->SearchValue2 = @$filter["y_User_Phone_Number"];
        $this->User_Phone_Number->AdvancedSearch->SearchOperator2 = @$filter["w_User_Phone_Number"];
        $this->User_Phone_Number->AdvancedSearch->save();

        // Field Address_Name
        $this->Address_Name->AdvancedSearch->SearchValue = @$filter["x_Address_Name"];
        $this->Address_Name->AdvancedSearch->SearchOperator = @$filter["z_Address_Name"];
        $this->Address_Name->AdvancedSearch->SearchCondition = @$filter["v_Address_Name"];
        $this->Address_Name->AdvancedSearch->SearchValue2 = @$filter["y_Address_Name"];
        $this->Address_Name->AdvancedSearch->SearchOperator2 = @$filter["w_Address_Name"];
        $this->Address_Name->AdvancedSearch->save();

        // Field Address_Street
        $this->Address_Street->AdvancedSearch->SearchValue = @$filter["x_Address_Street"];
        $this->Address_Street->AdvancedSearch->SearchOperator = @$filter["z_Address_Street"];
        $this->Address_Street->AdvancedSearch->SearchCondition = @$filter["v_Address_Street"];
        $this->Address_Street->AdvancedSearch->SearchValue2 = @$filter["y_Address_Street"];
        $this->Address_Street->AdvancedSearch->SearchOperator2 = @$filter["w_Address_Street"];
        $this->Address_Street->AdvancedSearch->save();

        // Field Address_Zipcode
        $this->Address_Zipcode->AdvancedSearch->SearchValue = @$filter["x_Address_Zipcode"];
        $this->Address_Zipcode->AdvancedSearch->SearchOperator = @$filter["z_Address_Zipcode"];
        $this->Address_Zipcode->AdvancedSearch->SearchCondition = @$filter["v_Address_Zipcode"];
        $this->Address_Zipcode->AdvancedSearch->SearchValue2 = @$filter["y_Address_Zipcode"];
        $this->Address_Zipcode->AdvancedSearch->SearchOperator2 = @$filter["w_Address_Zipcode"];
        $this->Address_Zipcode->AdvancedSearch->save();

        // Field Address_City
        $this->Address_City->AdvancedSearch->SearchValue = @$filter["x_Address_City"];
        $this->Address_City->AdvancedSearch->SearchOperator = @$filter["z_Address_City"];
        $this->Address_City->AdvancedSearch->SearchCondition = @$filter["v_Address_City"];
        $this->Address_City->AdvancedSearch->SearchValue2 = @$filter["y_Address_City"];
        $this->Address_City->AdvancedSearch->SearchOperator2 = @$filter["w_Address_City"];
        $this->Address_City->AdvancedSearch->save();

        // Field Address_Country
        $this->Address_Country->AdvancedSearch->SearchValue = @$filter["x_Address_Country"];
        $this->Address_Country->AdvancedSearch->SearchOperator = @$filter["z_Address_Country"];
        $this->Address_Country->AdvancedSearch->SearchCondition = @$filter["v_Address_Country"];
        $this->Address_Country->AdvancedSearch->SearchValue2 = @$filter["y_Address_Country"];
        $this->Address_Country->AdvancedSearch->SearchOperator2 = @$filter["w_Address_Country"];
        $this->Address_Country->AdvancedSearch->save();

        // Field Component_Type
        $this->Component_Type->AdvancedSearch->SearchValue = @$filter["x_Component_Type"];
        $this->Component_Type->AdvancedSearch->SearchOperator = @$filter["z_Component_Type"];
        $this->Component_Type->AdvancedSearch->SearchCondition = @$filter["v_Component_Type"];
        $this->Component_Type->AdvancedSearch->SearchValue2 = @$filter["y_Component_Type"];
        $this->Component_Type->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Type"];
        $this->Component_Type->AdvancedSearch->save();

        // Field Component_Category
        $this->Component_Category->AdvancedSearch->SearchValue = @$filter["x_Component_Category"];
        $this->Component_Category->AdvancedSearch->SearchOperator = @$filter["z_Component_Category"];
        $this->Component_Category->AdvancedSearch->SearchCondition = @$filter["v_Component_Category"];
        $this->Component_Category->AdvancedSearch->SearchValue2 = @$filter["y_Component_Category"];
        $this->Component_Category->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Category"];
        $this->Component_Category->AdvancedSearch->save();

        // Field Component_Make
        $this->Component_Make->AdvancedSearch->SearchValue = @$filter["x_Component_Make"];
        $this->Component_Make->AdvancedSearch->SearchOperator = @$filter["z_Component_Make"];
        $this->Component_Make->AdvancedSearch->SearchCondition = @$filter["v_Component_Make"];
        $this->Component_Make->AdvancedSearch->SearchValue2 = @$filter["y_Component_Make"];
        $this->Component_Make->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Make"];
        $this->Component_Make->AdvancedSearch->save();

        // Field Component_Model
        $this->Component_Model->AdvancedSearch->SearchValue = @$filter["x_Component_Model"];
        $this->Component_Model->AdvancedSearch->SearchOperator = @$filter["z_Component_Model"];
        $this->Component_Model->AdvancedSearch->SearchCondition = @$filter["v_Component_Model"];
        $this->Component_Model->AdvancedSearch->SearchValue2 = @$filter["y_Component_Model"];
        $this->Component_Model->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Model"];
        $this->Component_Model->AdvancedSearch->save();

        // Field Component_Serial_Number
        $this->Component_Serial_Number->AdvancedSearch->SearchValue = @$filter["x_Component_Serial_Number"];
        $this->Component_Serial_Number->AdvancedSearch->SearchOperator = @$filter["z_Component_Serial_Number"];
        $this->Component_Serial_Number->AdvancedSearch->SearchCondition = @$filter["v_Component_Serial_Number"];
        $this->Component_Serial_Number->AdvancedSearch->SearchValue2 = @$filter["y_Component_Serial_Number"];
        $this->Component_Serial_Number->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Serial_Number"];
        $this->Component_Serial_Number->AdvancedSearch->save();

        // Field Component_Display_Size
        $this->Component_Display_Size->AdvancedSearch->SearchValue = @$filter["x_Component_Display_Size"];
        $this->Component_Display_Size->AdvancedSearch->SearchOperator = @$filter["z_Component_Display_Size"];
        $this->Component_Display_Size->AdvancedSearch->SearchCondition = @$filter["v_Component_Display_Size"];
        $this->Component_Display_Size->AdvancedSearch->SearchValue2 = @$filter["y_Component_Display_Size"];
        $this->Component_Display_Size->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Display_Size"];
        $this->Component_Display_Size->AdvancedSearch->save();

        // Field Component_Keyboard_Layout
        $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue = @$filter["x_Component_Keyboard_Layout"];
        $this->Component_Keyboard_Layout->AdvancedSearch->SearchOperator = @$filter["z_Component_Keyboard_Layout"];
        $this->Component_Keyboard_Layout->AdvancedSearch->SearchCondition = @$filter["v_Component_Keyboard_Layout"];
        $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue2 = @$filter["y_Component_Keyboard_Layout"];
        $this->Component_Keyboard_Layout->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Keyboard_Layout"];
        $this->Component_Keyboard_Layout->AdvancedSearch->save();

        // Field Component_Type1
        $this->Component_Type1->AdvancedSearch->SearchValue = @$filter["x_Component_Type1"];
        $this->Component_Type1->AdvancedSearch->SearchOperator = @$filter["z_Component_Type1"];
        $this->Component_Type1->AdvancedSearch->SearchCondition = @$filter["v_Component_Type1"];
        $this->Component_Type1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Type1"];
        $this->Component_Type1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Type1"];
        $this->Component_Type1->AdvancedSearch->save();

        // Field Component_Category1
        $this->Component_Category1->AdvancedSearch->SearchValue = @$filter["x_Component_Category1"];
        $this->Component_Category1->AdvancedSearch->SearchOperator = @$filter["z_Component_Category1"];
        $this->Component_Category1->AdvancedSearch->SearchCondition = @$filter["v_Component_Category1"];
        $this->Component_Category1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Category1"];
        $this->Component_Category1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Category1"];
        $this->Component_Category1->AdvancedSearch->save();

        // Field Component_Make1
        $this->Component_Make1->AdvancedSearch->SearchValue = @$filter["x_Component_Make1"];
        $this->Component_Make1->AdvancedSearch->SearchOperator = @$filter["z_Component_Make1"];
        $this->Component_Make1->AdvancedSearch->SearchCondition = @$filter["v_Component_Make1"];
        $this->Component_Make1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Make1"];
        $this->Component_Make1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Make1"];
        $this->Component_Make1->AdvancedSearch->save();

        // Field Component_Model1
        $this->Component_Model1->AdvancedSearch->SearchValue = @$filter["x_Component_Model1"];
        $this->Component_Model1->AdvancedSearch->SearchOperator = @$filter["z_Component_Model1"];
        $this->Component_Model1->AdvancedSearch->SearchCondition = @$filter["v_Component_Model1"];
        $this->Component_Model1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Model1"];
        $this->Component_Model1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Model1"];
        $this->Component_Model1->AdvancedSearch->save();

        // Field Component_Serial_Number1
        $this->Component_Serial_Number1->AdvancedSearch->SearchValue = @$filter["x_Component_Serial_Number1"];
        $this->Component_Serial_Number1->AdvancedSearch->SearchOperator = @$filter["z_Component_Serial_Number1"];
        $this->Component_Serial_Number1->AdvancedSearch->SearchCondition = @$filter["v_Component_Serial_Number1"];
        $this->Component_Serial_Number1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Serial_Number1"];
        $this->Component_Serial_Number1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Serial_Number1"];
        $this->Component_Serial_Number1->AdvancedSearch->save();

        // Field Component_Display_Size1
        $this->Component_Display_Size1->AdvancedSearch->SearchValue = @$filter["x_Component_Display_Size1"];
        $this->Component_Display_Size1->AdvancedSearch->SearchOperator = @$filter["z_Component_Display_Size1"];
        $this->Component_Display_Size1->AdvancedSearch->SearchCondition = @$filter["v_Component_Display_Size1"];
        $this->Component_Display_Size1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Display_Size1"];
        $this->Component_Display_Size1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Display_Size1"];
        $this->Component_Display_Size1->AdvancedSearch->save();

        // Field Component_Keyboard_Layout1
        $this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue = @$filter["x_Component_Keyboard_Layout1"];
        $this->Component_Keyboard_Layout1->AdvancedSearch->SearchOperator = @$filter["z_Component_Keyboard_Layout1"];
        $this->Component_Keyboard_Layout1->AdvancedSearch->SearchCondition = @$filter["v_Component_Keyboard_Layout1"];
        $this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue2 = @$filter["y_Component_Keyboard_Layout1"];
        $this->Component_Keyboard_Layout1->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Keyboard_Layout1"];
        $this->Component_Keyboard_Layout1->AdvancedSearch->save();

        // Field Component_Type2
        $this->Component_Type2->AdvancedSearch->SearchValue = @$filter["x_Component_Type2"];
        $this->Component_Type2->AdvancedSearch->SearchOperator = @$filter["z_Component_Type2"];
        $this->Component_Type2->AdvancedSearch->SearchCondition = @$filter["v_Component_Type2"];
        $this->Component_Type2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Type2"];
        $this->Component_Type2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Type2"];
        $this->Component_Type2->AdvancedSearch->save();

        // Field Component_Category2
        $this->Component_Category2->AdvancedSearch->SearchValue = @$filter["x_Component_Category2"];
        $this->Component_Category2->AdvancedSearch->SearchOperator = @$filter["z_Component_Category2"];
        $this->Component_Category2->AdvancedSearch->SearchCondition = @$filter["v_Component_Category2"];
        $this->Component_Category2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Category2"];
        $this->Component_Category2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Category2"];
        $this->Component_Category2->AdvancedSearch->save();

        // Field Component_Make2
        $this->Component_Make2->AdvancedSearch->SearchValue = @$filter["x_Component_Make2"];
        $this->Component_Make2->AdvancedSearch->SearchOperator = @$filter["z_Component_Make2"];
        $this->Component_Make2->AdvancedSearch->SearchCondition = @$filter["v_Component_Make2"];
        $this->Component_Make2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Make2"];
        $this->Component_Make2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Make2"];
        $this->Component_Make2->AdvancedSearch->save();

        // Field Component_Model2
        $this->Component_Model2->AdvancedSearch->SearchValue = @$filter["x_Component_Model2"];
        $this->Component_Model2->AdvancedSearch->SearchOperator = @$filter["z_Component_Model2"];
        $this->Component_Model2->AdvancedSearch->SearchCondition = @$filter["v_Component_Model2"];
        $this->Component_Model2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Model2"];
        $this->Component_Model2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Model2"];
        $this->Component_Model2->AdvancedSearch->save();

        // Field Component_Serial_Number2
        $this->Component_Serial_Number2->AdvancedSearch->SearchValue = @$filter["x_Component_Serial_Number2"];
        $this->Component_Serial_Number2->AdvancedSearch->SearchOperator = @$filter["z_Component_Serial_Number2"];
        $this->Component_Serial_Number2->AdvancedSearch->SearchCondition = @$filter["v_Component_Serial_Number2"];
        $this->Component_Serial_Number2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Serial_Number2"];
        $this->Component_Serial_Number2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Serial_Number2"];
        $this->Component_Serial_Number2->AdvancedSearch->save();

        // Field Component_Display_Size2
        $this->Component_Display_Size2->AdvancedSearch->SearchValue = @$filter["x_Component_Display_Size2"];
        $this->Component_Display_Size2->AdvancedSearch->SearchOperator = @$filter["z_Component_Display_Size2"];
        $this->Component_Display_Size2->AdvancedSearch->SearchCondition = @$filter["v_Component_Display_Size2"];
        $this->Component_Display_Size2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Display_Size2"];
        $this->Component_Display_Size2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Display_Size2"];
        $this->Component_Display_Size2->AdvancedSearch->save();

        // Field Component_Keyboard_Layout2
        $this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue = @$filter["x_Component_Keyboard_Layout2"];
        $this->Component_Keyboard_Layout2->AdvancedSearch->SearchOperator = @$filter["z_Component_Keyboard_Layout2"];
        $this->Component_Keyboard_Layout2->AdvancedSearch->SearchCondition = @$filter["v_Component_Keyboard_Layout2"];
        $this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue2 = @$filter["y_Component_Keyboard_Layout2"];
        $this->Component_Keyboard_Layout2->AdvancedSearch->SearchOperator2 = @$filter["w_Component_Keyboard_Layout2"];
        $this->Component_Keyboard_Layout2->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Advanced search WHERE clause based on QueryString
    public function advancedSearchWhere($default = false)
    {
        global $Security;
        $where = "";
        $this->buildSearchSql($where, $this->Workstation_Name, $default, true); // Workstation_Name
        $this->buildSearchSql($where, $this->Workstation_Remark, $default, true); // Workstation_Remark
        $this->buildSearchSql($where, $this->User_Email, $default, true); // User_Email
        $this->buildSearchSql($where, $this->User_Name, $default, true); // User_Name
        $this->buildSearchSql($where, $this->User_Employee_Number, $default, true); // User_Employee_Number
        $this->buildSearchSql($where, $this->User_Phone_Number, $default, true); // User_Phone_Number
        $this->buildSearchSql($where, $this->Address_Name, $default, true); // Address_Name
        $this->buildSearchSql($where, $this->Address_Street, $default, true); // Address_Street
        $this->buildSearchSql($where, $this->Address_Zipcode, $default, true); // Address_Zipcode
        $this->buildSearchSql($where, $this->Address_City, $default, true); // Address_City
        $this->buildSearchSql($where, $this->Address_Country, $default, true); // Address_Country
        $this->buildSearchSql($where, $this->Component_Type, $default, true); // Component_Type
        $this->buildSearchSql($where, $this->Component_Category, $default, true); // Component_Category
        $this->buildSearchSql($where, $this->Component_Make, $default, true); // Component_Make
        $this->buildSearchSql($where, $this->Component_Model, $default, true); // Component_Model
        $this->buildSearchSql($where, $this->Component_Serial_Number, $default, true); // Component_Serial_Number
        $this->buildSearchSql($where, $this->Component_Display_Size, $default, true); // Component_Display_Size
        $this->buildSearchSql($where, $this->Component_Keyboard_Layout, $default, true); // Component_Keyboard_Layout
        $this->buildSearchSql($where, $this->Component_Type1, $default, true); // Component_Type1
        $this->buildSearchSql($where, $this->Component_Category1, $default, true); // Component_Category1
        $this->buildSearchSql($where, $this->Component_Make1, $default, true); // Component_Make1
        $this->buildSearchSql($where, $this->Component_Model1, $default, true); // Component_Model1
        $this->buildSearchSql($where, $this->Component_Serial_Number1, $default, true); // Component_Serial_Number1
        $this->buildSearchSql($where, $this->Component_Display_Size1, $default, true); // Component_Display_Size1
        $this->buildSearchSql($where, $this->Component_Keyboard_Layout1, $default, true); // Component_Keyboard_Layout1
        $this->buildSearchSql($where, $this->Component_Type2, $default, true); // Component_Type2
        $this->buildSearchSql($where, $this->Component_Category2, $default, true); // Component_Category2
        $this->buildSearchSql($where, $this->Component_Make2, $default, true); // Component_Make2
        $this->buildSearchSql($where, $this->Component_Model2, $default, true); // Component_Model2
        $this->buildSearchSql($where, $this->Component_Serial_Number2, $default, true); // Component_Serial_Number2
        $this->buildSearchSql($where, $this->Component_Display_Size2, $default, true); // Component_Display_Size2
        $this->buildSearchSql($where, $this->Component_Keyboard_Layout2, $default, true); // Component_Keyboard_Layout2

        // Set up search command
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->Workstation_Name->AdvancedSearch->save(); // Workstation_Name
            $this->Workstation_Remark->AdvancedSearch->save(); // Workstation_Remark
            $this->User_Email->AdvancedSearch->save(); // User_Email
            $this->User_Name->AdvancedSearch->save(); // User_Name
            $this->User_Employee_Number->AdvancedSearch->save(); // User_Employee_Number
            $this->User_Phone_Number->AdvancedSearch->save(); // User_Phone_Number
            $this->Address_Name->AdvancedSearch->save(); // Address_Name
            $this->Address_Street->AdvancedSearch->save(); // Address_Street
            $this->Address_Zipcode->AdvancedSearch->save(); // Address_Zipcode
            $this->Address_City->AdvancedSearch->save(); // Address_City
            $this->Address_Country->AdvancedSearch->save(); // Address_Country
            $this->Component_Type->AdvancedSearch->save(); // Component_Type
            $this->Component_Category->AdvancedSearch->save(); // Component_Category
            $this->Component_Make->AdvancedSearch->save(); // Component_Make
            $this->Component_Model->AdvancedSearch->save(); // Component_Model
            $this->Component_Serial_Number->AdvancedSearch->save(); // Component_Serial_Number
            $this->Component_Display_Size->AdvancedSearch->save(); // Component_Display_Size
            $this->Component_Keyboard_Layout->AdvancedSearch->save(); // Component_Keyboard_Layout
            $this->Component_Type1->AdvancedSearch->save(); // Component_Type1
            $this->Component_Category1->AdvancedSearch->save(); // Component_Category1
            $this->Component_Make1->AdvancedSearch->save(); // Component_Make1
            $this->Component_Model1->AdvancedSearch->save(); // Component_Model1
            $this->Component_Serial_Number1->AdvancedSearch->save(); // Component_Serial_Number1
            $this->Component_Display_Size1->AdvancedSearch->save(); // Component_Display_Size1
            $this->Component_Keyboard_Layout1->AdvancedSearch->save(); // Component_Keyboard_Layout1
            $this->Component_Type2->AdvancedSearch->save(); // Component_Type2
            $this->Component_Category2->AdvancedSearch->save(); // Component_Category2
            $this->Component_Make2->AdvancedSearch->save(); // Component_Make2
            $this->Component_Model2->AdvancedSearch->save(); // Component_Model2
            $this->Component_Serial_Number2->AdvancedSearch->save(); // Component_Serial_Number2
            $this->Component_Display_Size2->AdvancedSearch->save(); // Component_Display_Size2
            $this->Component_Keyboard_Layout2->AdvancedSearch->save(); // Component_Keyboard_Layout2

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $where;
    }

    // Parse query builder rule function
    protected function parseRules($group, $fieldName = "") {
        $group["condition"] ??= "AND";
        if (!in_array($group["condition"], ["AND", "OR"])) {
            throw new \Exception("Unable to build SQL query with condition '" . $group["condition"] . "'");
        }
        if (!is_array($group["rules"] ?? null)) {
            return "";
        }
        $parts = [];
        foreach ($group["rules"] as $rule) {
            if (is_array($rule["rules"] ?? null) && count($rule["rules"]) > 0) {
                $parts[] = "(" . " " . $this->parseRules($rule, $fieldName) . " " . ")" . " ";
            } else {
                $field = $rule["field"];
                $fld = $this->fieldByParam($field);
                if (!$fld) {
                    throw new \Exception("Failed to find field '" . $field . "'");
                }
                if ($fieldName == "" || $fld->Name == $fieldName) { // Field name not specified or matched field name
                    $fldOpr = array_search($rule["operator"], Config("CLIENT_SEARCH_OPERATORS"));
                    $ope = Config("QUERY_BUILDER_OPERATORS")[$rule["operator"]] ?? null;
                    if (!$ope || !$fldOpr) {
                        throw new \Exception("Unknown SQL operation for operator '" . $rule["operator"] . "'");
                    }
                    if ($ope["nb_inputs"] > 0 && ($rule["value"] ?? false) || IsNullOrEmptyOperator($fldOpr)) {
                        $rule["value"] = !is_array($rule["value"]) ? [$rule["value"]] : $rule["value"];
                        $fldVal = $rule["value"][0];
                        if (is_array($fldVal)) {
                            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
                        }
                        $useFilter = $fld->UseFilter; // Query builder does not use filter
                        try {
                            if ($fld->isMultiSelect()) {
                                $parts[] = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, ConvertSearchValue($fldVal, $fldOpr, $fld), $this->Dbid) : "";
                            } else {
                                $fldVal2 = ContainsString($fldOpr, "BETWEEN") ? $rule["value"][1] : ""; // BETWEEN
                                if (is_array($fldVal2)) {
                                    $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
                                }
                                $parts[] = GetSearchSql(
                                    $fld,
                                    ConvertSearchValue($fldVal, $fldOpr, $fld), // $fldVal
                                    $fldOpr,
                                    "", // $fldCond not used
                                    ConvertSearchValue($fldVal2, $fldOpr, $fld), // $fldVal2
                                    "", // $fldOpr2 not used
                                    $this->Dbid
                                );
                            }
                        } finally {
                            $fld->UseFilter = $useFilter;
                        }
                    }
                }
            }
        }
        $where = implode(" " . $group["condition"] . " ", array_filter($parts));
        if ($group["not"] ?? false) {
            $where = "NOT (" . $where . ")";
        }
        return $where;
    }

    // Quey builder WHERE clause
    public function queryBuilderWhere($fieldName = "")
    {
        global $Security;

        // Get rules by query builder
        $rules = Post("rules") ?? $this->getSessionRules();

        // Decode and parse rules
        $where = $rules ? $this->parseRules(json_decode($rules, true), $fieldName) : "";

        // Clear other search and save rules to session
        if ($where && $fieldName == "") { // Skip if get query for specific field
            $this->resetSearchParms();
            $this->setSessionRules($rules);
        }

        // Return query
        return $where;
    }

    // Build search SQL
    protected function buildSearchSql(&$where, $fld, $default, $multiValue)
    {
        $fldParm = $fld->Param;
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldCond = $default ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        $fldOpr2 = $default ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
        $fldVal = ConvertSearchValue($fldVal, $fldOpr, $fld);
        $fldVal2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld);
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $fldVal);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $fldVal2);
        $wrk = "";
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        if (Config("SEARCH_MULTI_VALUE_OPTION") == 1 && !$fld->UseFilter || !IsMultiSearchOperator($fldOpr)) {
            $multiValue = false;
        }
        if ($multiValue) {
            $wrk = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, $fldVal, $this->Dbid) : ""; // Field value 1
            $wrk2 = $fldVal2 != "" ? GetMultiSearchSql($fld, $fldOpr2, $fldVal2, $this->Dbid) : ""; // Field value 2
            AddFilter($wrk, $wrk2, $fldCond);
        } else {
            $wrk = GetSearchSql($fld, $fldVal, $fldOpr, $fldCond, $fldVal2, $fldOpr2, $this->Dbid);
        }
        if ($this->SearchOption == "AUTO" && in_array($this->BasicSearch->getType(), ["AND", "OR"])) {
            $cond = $this->BasicSearch->getType();
        } else {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
        }
        AddFilter($where, $wrk, $cond);
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field Workstation_Name
        $filter = $this->queryBuilderWhere("Workstation_Name");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Workstation_Name, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Workstation_Name->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Workstation_Remark
        $filter = $this->queryBuilderWhere("Workstation_Remark");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Workstation_Remark, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Workstation_Remark->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field User_Email
        $filter = $this->queryBuilderWhere("User_Email");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->User_Email, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->User_Email->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field User_Name
        $filter = $this->queryBuilderWhere("User_Name");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->User_Name, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->User_Name->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field User_Employee_Number
        $filter = $this->queryBuilderWhere("User_Employee_Number");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->User_Employee_Number, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->User_Employee_Number->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field User_Phone_Number
        $filter = $this->queryBuilderWhere("User_Phone_Number");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->User_Phone_Number, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->User_Phone_Number->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Address_Name
        $filter = $this->queryBuilderWhere("Address_Name");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Address_Name, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Address_Name->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Address_Street
        $filter = $this->queryBuilderWhere("Address_Street");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Address_Street, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Address_Street->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Address_Zipcode
        $filter = $this->queryBuilderWhere("Address_Zipcode");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Address_Zipcode, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Address_Zipcode->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Address_City
        $filter = $this->queryBuilderWhere("Address_City");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Address_City, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Address_City->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Address_Country
        $filter = $this->queryBuilderWhere("Address_Country");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Address_Country, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Address_Country->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Type
        $filter = $this->queryBuilderWhere("Component_Type");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Type, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Type->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Category
        $filter = $this->queryBuilderWhere("Component_Category");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Category, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Category->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Make
        $filter = $this->queryBuilderWhere("Component_Make");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Make, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Make->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Model
        $filter = $this->queryBuilderWhere("Component_Model");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Model, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Model->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Serial_Number
        $filter = $this->queryBuilderWhere("Component_Serial_Number");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Serial_Number, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Serial_Number->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Display_Size
        $filter = $this->queryBuilderWhere("Component_Display_Size");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Display_Size, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Display_Size->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Keyboard_Layout
        $filter = $this->queryBuilderWhere("Component_Keyboard_Layout");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Keyboard_Layout, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Keyboard_Layout->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Type1
        $filter = $this->queryBuilderWhere("Component_Type1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Type1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Type1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Category1
        $filter = $this->queryBuilderWhere("Component_Category1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Category1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Category1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Make1
        $filter = $this->queryBuilderWhere("Component_Make1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Make1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Make1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Model1
        $filter = $this->queryBuilderWhere("Component_Model1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Model1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Model1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Serial_Number1
        $filter = $this->queryBuilderWhere("Component_Serial_Number1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Serial_Number1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Serial_Number1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Display_Size1
        $filter = $this->queryBuilderWhere("Component_Display_Size1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Display_Size1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Display_Size1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Keyboard_Layout1
        $filter = $this->queryBuilderWhere("Component_Keyboard_Layout1");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Keyboard_Layout1, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Keyboard_Layout1->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Type2
        $filter = $this->queryBuilderWhere("Component_Type2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Type2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Type2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Category2
        $filter = $this->queryBuilderWhere("Component_Category2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Category2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Category2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Make2
        $filter = $this->queryBuilderWhere("Component_Make2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Make2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Make2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Model2
        $filter = $this->queryBuilderWhere("Component_Model2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Model2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Model2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Serial_Number2
        $filter = $this->queryBuilderWhere("Component_Serial_Number2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Serial_Number2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Serial_Number2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Display_Size2
        $filter = $this->queryBuilderWhere("Component_Display_Size2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Display_Size2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Display_Size2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Component_Keyboard_Layout2
        $filter = $this->queryBuilderWhere("Component_Keyboard_Layout2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->Component_Keyboard_Layout2, false, true);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Component_Keyboard_Layout2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }
        if ($this->BasicSearch->Keyword != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $Language->phrase("BasicSearchKeyword") . "</span>" . $captionSuffix . $this->BasicSearch->Keyword . "</div>";
        }

        // Show Filters
        if ($filterList != "") {
            $message = "<div id=\"ew-filter-list\" class=\"callout callout-info d-table\"><div id=\"ew-current-filters\">" .
                $Language->phrase("CurrentFilters") . "</div>" . $filterList . "</div>";
            $this->messageShowing($message, "");
            Write($message);
        } else { // Output empty tag
            Write("<div id=\"ew-filter-list\"></div>");
        }
    }

    // Return basic search WHERE clause based on search keyword and type
    public function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";

        // Fields to search
        $searchFlds = [];
        $searchFlds[] = &$this->Workstation_Name;
        $searchFlds[] = &$this->Workstation_Remark;
        $searchFlds[] = &$this->User_Email;
        $searchFlds[] = &$this->User_Name;
        $searchFlds[] = &$this->User_Employee_Number;
        $searchFlds[] = &$this->User_Phone_Number;
        $searchFlds[] = &$this->Address_Name;
        $searchFlds[] = &$this->Address_Street;
        $searchFlds[] = &$this->Address_Zipcode;
        $searchFlds[] = &$this->Address_City;
        $searchFlds[] = &$this->Address_Country;
        $searchFlds[] = &$this->Component_Type;
        $searchFlds[] = &$this->Component_Category;
        $searchFlds[] = &$this->Component_Make;
        $searchFlds[] = &$this->Component_Model;
        $searchFlds[] = &$this->Component_Serial_Number;
        $searchFlds[] = &$this->Component_Display_Size;
        $searchFlds[] = &$this->Component_Keyboard_Layout;
        $searchFlds[] = &$this->Component_Type1;
        $searchFlds[] = &$this->Component_Category1;
        $searchFlds[] = &$this->Component_Make1;
        $searchFlds[] = &$this->Component_Model1;
        $searchFlds[] = &$this->Component_Serial_Number1;
        $searchFlds[] = &$this->Component_Display_Size1;
        $searchFlds[] = &$this->Component_Keyboard_Layout1;
        $searchFlds[] = &$this->Component_Type2;
        $searchFlds[] = &$this->Component_Category2;
        $searchFlds[] = &$this->Component_Make2;
        $searchFlds[] = &$this->Component_Model2;
        $searchFlds[] = &$this->Component_Serial_Number2;
        $searchFlds[] = &$this->Component_Display_Size2;
        $searchFlds[] = &$this->Component_Keyboard_Layout2;
        $searchKeyword = $default ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = $default ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            $searchStr = GetQuickSearchFilter($searchFlds, $ar, $searchType, Config("BASIC_SEARCH_ANY_FIELDS"), $this->Dbid);
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        if ($this->Workstation_Name->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Workstation_Remark->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->User_Email->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->User_Name->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->User_Employee_Number->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->User_Phone_Number->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Address_Name->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Address_Street->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Address_Zipcode->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Address_City->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Address_Country->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Type->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Category->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Make->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Model->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Serial_Number->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Display_Size->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Keyboard_Layout->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Type1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Category1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Make1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Model1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Serial_Number1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Display_Size1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Keyboard_Layout1->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Type2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Category2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Make2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Model2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Serial_Number2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Display_Size2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->Component_Keyboard_Layout2->AdvancedSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();

        // Clear advanced search parameters
        $this->resetAdvancedSearchParms();

        // Clear queryBuilder
        $this->setSessionRules("");
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
        return false;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Clear all advanced search parameters
    protected function resetAdvancedSearchParms()
    {
        $this->Workstation_Name->AdvancedSearch->unsetSession();
        $this->Workstation_Remark->AdvancedSearch->unsetSession();
        $this->User_Email->AdvancedSearch->unsetSession();
        $this->User_Name->AdvancedSearch->unsetSession();
        $this->User_Employee_Number->AdvancedSearch->unsetSession();
        $this->User_Phone_Number->AdvancedSearch->unsetSession();
        $this->Address_Name->AdvancedSearch->unsetSession();
        $this->Address_Street->AdvancedSearch->unsetSession();
        $this->Address_Zipcode->AdvancedSearch->unsetSession();
        $this->Address_City->AdvancedSearch->unsetSession();
        $this->Address_Country->AdvancedSearch->unsetSession();
        $this->Component_Type->AdvancedSearch->unsetSession();
        $this->Component_Category->AdvancedSearch->unsetSession();
        $this->Component_Make->AdvancedSearch->unsetSession();
        $this->Component_Model->AdvancedSearch->unsetSession();
        $this->Component_Serial_Number->AdvancedSearch->unsetSession();
        $this->Component_Display_Size->AdvancedSearch->unsetSession();
        $this->Component_Keyboard_Layout->AdvancedSearch->unsetSession();
        $this->Component_Type1->AdvancedSearch->unsetSession();
        $this->Component_Category1->AdvancedSearch->unsetSession();
        $this->Component_Make1->AdvancedSearch->unsetSession();
        $this->Component_Model1->AdvancedSearch->unsetSession();
        $this->Component_Serial_Number1->AdvancedSearch->unsetSession();
        $this->Component_Display_Size1->AdvancedSearch->unsetSession();
        $this->Component_Keyboard_Layout1->AdvancedSearch->unsetSession();
        $this->Component_Type2->AdvancedSearch->unsetSession();
        $this->Component_Category2->AdvancedSearch->unsetSession();
        $this->Component_Make2->AdvancedSearch->unsetSession();
        $this->Component_Model2->AdvancedSearch->unsetSession();
        $this->Component_Serial_Number2->AdvancedSearch->unsetSession();
        $this->Component_Display_Size2->AdvancedSearch->unsetSession();
        $this->Component_Keyboard_Layout2->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->Workstation_Name->AdvancedSearch->load();
        $this->Workstation_Remark->AdvancedSearch->load();
        $this->User_Email->AdvancedSearch->load();
        $this->User_Name->AdvancedSearch->load();
        $this->User_Employee_Number->AdvancedSearch->load();
        $this->User_Phone_Number->AdvancedSearch->load();
        $this->Address_Name->AdvancedSearch->load();
        $this->Address_Street->AdvancedSearch->load();
        $this->Address_Zipcode->AdvancedSearch->load();
        $this->Address_City->AdvancedSearch->load();
        $this->Address_Country->AdvancedSearch->load();
        $this->Component_Type->AdvancedSearch->load();
        $this->Component_Category->AdvancedSearch->load();
        $this->Component_Make->AdvancedSearch->load();
        $this->Component_Model->AdvancedSearch->load();
        $this->Component_Serial_Number->AdvancedSearch->load();
        $this->Component_Display_Size->AdvancedSearch->load();
        $this->Component_Keyboard_Layout->AdvancedSearch->load();
        $this->Component_Type1->AdvancedSearch->load();
        $this->Component_Category1->AdvancedSearch->load();
        $this->Component_Make1->AdvancedSearch->load();
        $this->Component_Model1->AdvancedSearch->load();
        $this->Component_Serial_Number1->AdvancedSearch->load();
        $this->Component_Display_Size1->AdvancedSearch->load();
        $this->Component_Keyboard_Layout1->AdvancedSearch->load();
        $this->Component_Type2->AdvancedSearch->load();
        $this->Component_Category2->AdvancedSearch->load();
        $this->Component_Make2->AdvancedSearch->load();
        $this->Component_Model2->AdvancedSearch->load();
        $this->Component_Serial_Number2->AdvancedSearch->load();
        $this->Component_Display_Size2->AdvancedSearch->load();
        $this->Component_Keyboard_Layout2->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = ""; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->Workstation_Name); // Workstation_Name
            $this->updateSort($this->Workstation_Remark); // Workstation_Remark
            $this->updateSort($this->User_Email); // User_Email
            $this->updateSort($this->User_Name); // User_Name
            $this->updateSort($this->User_Employee_Number); // User_Employee_Number
            $this->updateSort($this->User_Phone_Number); // User_Phone_Number
            $this->updateSort($this->Address_Name); // Address_Name
            $this->updateSort($this->Address_Street); // Address_Street
            $this->updateSort($this->Address_Zipcode); // Address_Zipcode
            $this->updateSort($this->Address_City); // Address_City
            $this->updateSort($this->Address_Country); // Address_Country
            $this->updateSort($this->Component_Type); // Component_Type
            $this->updateSort($this->Component_Category); // Component_Category
            $this->updateSort($this->Component_Make); // Component_Make
            $this->updateSort($this->Component_Model); // Component_Model
            $this->updateSort($this->Component_Serial_Number); // Component_Serial_Number
            $this->updateSort($this->Component_Display_Size); // Component_Display_Size
            $this->updateSort($this->Component_Keyboard_Layout); // Component_Keyboard_Layout
            $this->updateSort($this->Component_Type1); // Component_Type1
            $this->updateSort($this->Component_Category1); // Component_Category1
            $this->updateSort($this->Component_Make1); // Component_Make1
            $this->updateSort($this->Component_Model1); // Component_Model1
            $this->updateSort($this->Component_Serial_Number1); // Component_Serial_Number1
            $this->updateSort($this->Component_Display_Size1); // Component_Display_Size1
            $this->updateSort($this->Component_Keyboard_Layout1); // Component_Keyboard_Layout1
            $this->updateSort($this->Component_Type2); // Component_Type2
            $this->updateSort($this->Component_Category2); // Component_Category2
            $this->updateSort($this->Component_Make2); // Component_Make2
            $this->updateSort($this->Component_Model2); // Component_Model2
            $this->updateSort($this->Component_Serial_Number2); // Component_Serial_Number2
            $this->updateSort($this->Component_Display_Size2); // Component_Display_Size2
            $this->updateSort($this->Component_Keyboard_Layout2); // Component_Keyboard_Layout2
            $this->setStartRecordNumber(1); // Reset start position
        }

        // Update field sort
        $this->updateFieldSort();
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->id->setSort("");
                $this->Workstation_Name->setSort("");
                $this->Workstation_Remark->setSort("");
                $this->User_Email->setSort("");
                $this->User_Name->setSort("");
                $this->User_Employee_Number->setSort("");
                $this->User_Phone_Number->setSort("");
                $this->Address_Name->setSort("");
                $this->Address_Street->setSort("");
                $this->Address_Zipcode->setSort("");
                $this->Address_City->setSort("");
                $this->Address_Country->setSort("");
                $this->Component_Type->setSort("");
                $this->Component_Category->setSort("");
                $this->Component_Make->setSort("");
                $this->Component_Model->setSort("");
                $this->Component_Serial_Number->setSort("");
                $this->Component_Display_Size->setSort("");
                $this->Component_Keyboard_Layout->setSort("");
                $this->Component_Type1->setSort("");
                $this->Component_Category1->setSort("");
                $this->Component_Make1->setSort("");
                $this->Component_Model1->setSort("");
                $this->Component_Serial_Number1->setSort("");
                $this->Component_Display_Size1->setSort("");
                $this->Component_Keyboard_Layout1->setSort("");
                $this->Component_Type2->setSort("");
                $this->Component_Category2->setSort("");
                $this->Component_Make2->setSort("");
                $this->Component_Model2->setSort("");
                $this->Component_Serial_Number2->setSort("");
                $this->Component_Display_Size2->setSort("");
                $this->Component_Keyboard_Layout2->setSort("");
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = true;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = true;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = ($Security->canDelete() || $Security->canEdit());
        $item->OnLeft = true;
        $item->Header = "<div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\"></div>";
        if ($item->OnLeft) {
            $item->moveTo(0);
        }
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // "sequence"
        $item = &$this->ListOptions->add("sequence");
        $item->CssClass = "text-nowrap";
        $item->Visible = true;
        $item->OnLeft = true; // Always on left
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $this->setupListOptionsExt();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Set up list options (extensions)
    protected function setupListOptionsExt()
    {
            // Set up list options (to be implemented by extensions)
    }

    // Add "hash" parameter to URL
    public function urlAddHash($url, $hash)
    {
        return $this->UseAjaxActions ? $url : UrlAddQuery($url, "hash=" . $hash);
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm, $UserProfile;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();

        // "sequence"
        $opt = $this->ListOptions["sequence"];
        $opt->Body = FormatSequenceNumber($this->RecordCount);
        $pageUrl = $this->pageUrl(false);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"workstation\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                if ($this->ModalEdit && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"workstation\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }
        } // End View mode

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions->Items as $listaction) {
                $action = $listaction->Action;
                $allowed = $listaction->Allow;
                if ($listaction->Select == ACTION_SINGLE && $allowed) {
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listaction->Icon)) . "\" data-caption=\"" . HtmlTitle($caption) . "\"></i> " : "";
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fworkstationlist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fworkstationlist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button>";
                        }
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = "";
                foreach ($links as $link) {
                    $content .= "<li>" . $link . "</li>";
                }
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
            }
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->id->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        if ($this->ModalAdd && !IsMobile()) {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"workstation\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        }
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        $option = $options["action"];

        // Add multi delete
        $item = &$option->add("multidelete");
        $item->Body = "<button type=\"button\" class=\"ew-action ew-multi-delete\" title=\"" .
            HtmlTitle($Language->phrase("DeleteSelectedLink")) . "\" data-caption=\"" .
            HtmlTitle($Language->phrase("DeleteSelectedLink")) . "\" form=\"fworkstationlist\"" .
            " data-ew-action=\"" . ($this->UseAjaxActions ? "inline" : "submit") . "\"" .
            ($this->UseAjaxActions ? " data-action=\"delete\"" : "") .
            " data-url=\"" . GetUrl($this->MultiDeleteUrl) . "\"" .
            ($this->InlineDelete ? " data-msg=\"" . HtmlEncode($Language->phrase("DeleteConfirm")) . "\" data-data='{\"action\":\"delete\"}'" : " data-data='{\"action\":\"show\"}'") .
            ">" . $Language->phrase("DeleteSelectedLink") . "</button>";
        $item->Visible = $Security->canDelete();

        // Add multi update
        $item = &$option->add("multiupdate");
        $item->Body = "<button type=\"button\" class=\"ew-action ew-multi-update\" title=\"" .
            $Language->phrase("UpdateSelectedLink", true) . "\" data-table=\"workstation\" data-caption=\"" .
            $Language->phrase("UpdateSelectedLink", true) . "\" form=\"fworkstationlist\" data-ew-action=\"" .
            ($this->ModalUpdate && !IsMobile() ? "modal" : "submit") . "\"" .
            ($this->ModalUpdate && !IsMobile() ? " data-action=\"update\"" : "") .
            ($this->UseAjaxActions ? " data-ajax=\"true\"" : "") .
            " data-url=\"" . GetUrl($this->MultiUpdateUrl) . "\">" . $Language->phrase("UpdateSelectedLink") . "</button>";
        $item->Visible = $Security->canEdit();

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $option->add("Workstation_Name", $this->createColumnOption("Workstation_Name"));
            $option->add("Workstation_Remark", $this->createColumnOption("Workstation_Remark"));
            $option->add("User_Email", $this->createColumnOption("User_Email"));
            $option->add("User_Name", $this->createColumnOption("User_Name"));
            $option->add("User_Employee_Number", $this->createColumnOption("User_Employee_Number"));
            $option->add("User_Phone_Number", $this->createColumnOption("User_Phone_Number"));
            $option->add("Address_Name", $this->createColumnOption("Address_Name"));
            $option->add("Address_Street", $this->createColumnOption("Address_Street"));
            $option->add("Address_Zipcode", $this->createColumnOption("Address_Zipcode"));
            $option->add("Address_City", $this->createColumnOption("Address_City"));
            $option->add("Address_Country", $this->createColumnOption("Address_Country"));
            $option->add("Component_Type", $this->createColumnOption("Component_Type"));
            $option->add("Component_Category", $this->createColumnOption("Component_Category"));
            $option->add("Component_Make", $this->createColumnOption("Component_Make"));
            $option->add("Component_Model", $this->createColumnOption("Component_Model"));
            $option->add("Component_Serial_Number", $this->createColumnOption("Component_Serial_Number"));
            $option->add("Component_Display_Size", $this->createColumnOption("Component_Display_Size"));
            $option->add("Component_Keyboard_Layout", $this->createColumnOption("Component_Keyboard_Layout"));
            $option->add("Component_Type1", $this->createColumnOption("Component_Type1"));
            $option->add("Component_Category1", $this->createColumnOption("Component_Category1"));
            $option->add("Component_Make1", $this->createColumnOption("Component_Make1"));
            $option->add("Component_Model1", $this->createColumnOption("Component_Model1"));
            $option->add("Component_Serial_Number1", $this->createColumnOption("Component_Serial_Number1"));
            $option->add("Component_Display_Size1", $this->createColumnOption("Component_Display_Size1"));
            $option->add("Component_Keyboard_Layout1", $this->createColumnOption("Component_Keyboard_Layout1"));
            $option->add("Component_Type2", $this->createColumnOption("Component_Type2"));
            $option->add("Component_Category2", $this->createColumnOption("Component_Category2"));
            $option->add("Component_Make2", $this->createColumnOption("Component_Make2"));
            $option->add("Component_Model2", $this->createColumnOption("Component_Model2"));
            $option->add("Component_Serial_Number2", $this->createColumnOption("Component_Serial_Number2"));
            $option->add("Component_Display_Size2", $this->createColumnOption("Component_Display_Size2"));
            $option->add("Component_Keyboard_Layout2", $this->createColumnOption("Component_Keyboard_Layout2"));
        }

        // Set up options default
        foreach ($options as $name => $option) {
            if ($name != "column") { // Always use dropdown for column
                $option->UseDropDownButton = false;
                $option->UseButtonGroup = true;
            }
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fworkstationsrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fworkstationsrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Create new column option
    public function createColumnOption($name)
    {
        $field = $this->Fields[$name] ?? false;
        if ($field && $field->Visible) {
            $item = new ListOption($field->Name);
            $item->Body = '<button class="dropdown-item">' .
                '<div class="form-check ew-dropdown-checkbox">' .
                '<div class="form-check-input ew-dropdown-check-input" data-field="' . $field->Param . '"></div>' .
                '<label class="form-check-label ew-dropdown-check-label">' . $field->caption() . '</label></div></button>';
            return $item;
        }
        return null;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];
        // Set up list action buttons
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE) {
                $item = &$option->add("custom_" . $listaction->Action);
                $caption = $listaction->Caption;
                $icon = ($listaction->Icon != "") ? '<i class="' . HtmlEncode($listaction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="fworkstationlist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
                $item->Visible = $listaction->Allow;
            }
        }

        // Hide multi edit, grid edit and other options
        if ($this->TotalRecords <= 0) {
            $option = $options["addedit"];
            $item = $option["gridedit"];
            if ($item) {
                $item->Visible = false;
            }
            $option = $options["action"];
            $option->hideAllOptions();
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security, $Response;
        $userlist = "";
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("action", "");
        if ($filter != "" && $userAction != "") {
            // Check permission first
            $actionCaption = $userAction;
            if (array_key_exists($userAction, $this->ListActions->Items)) {
                if (array_key_exists($userAction, $this->CustomActions)) {
                    $this->UserAction = $userAction;
                }
                $actionCaption = $this->ListActions[$userAction]->Caption;
                if (!$this->ListActions[$userAction]->Allow) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            }
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn);
            $this->ActionValue = Post("actionvalue");

            // Call row action event
            if ($rs) {
                if ($this->UseTransaction) {
                    $conn->beginTransaction();
                }
                $this->SelectedCount = $rs->recordCount();
                $this->SelectedIndex = 0;
                while (!$rs->EOF) {
                    $this->SelectedIndex++;
                    $row = $rs->fields;
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                    $rs->moveNext();
                }
                if ($processed) {
                    if ($this->UseTransaction) { // Commit transaction
                        $conn->commit();
                    }
                    if ($this->getSuccessMessage() == "" && !ob_get_length() && !$Response->getBody()->getSize()) { // No output
                        $this->setSuccessMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        $conn->rollback();
                    }

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if ($rs) {
                $rs->close();
            }
            if (Post("ajax") == $userAction) { // Ajax
                if ($this->getSuccessMessage() != "") {
                    echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                    $this->clearSuccessMessage(); // Clear message
                }
                if ($this->getFailureMessage() != "") {
                    echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                    $this->clearFailureMessage(); // Clear message
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Set up Grid
    public function setupGrid()
    {
        global $CurrentForm;
        if ($this->ExportAll && $this->isExport()) {
            $this->StopRecord = $this->TotalRecords;
        } else {
            // Set the last record to display
            if ($this->TotalRecords > $this->StartRecord + $this->DisplayRecords - 1) {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            } else {
                $this->StopRecord = $this->TotalRecords;
            }
        }
        $this->RecordCount = $this->StartRecord - 1;
        if ($this->Recordset && !$this->Recordset->EOF) {
            // Nothing to do
        } elseif ($this->isGridAdd() && !$this->AllowAddDeleteRow && $this->StopRecord == 0) { // Grid-Add with no records
            $this->StopRecord = $this->GridAddRowCount;
        } elseif ($this->isAdd() && $this->TotalRecords == 0) { // Inline-Add with no records
            $this->StopRecord = 1;
        }

        // Initialize aggregate
        $this->RowType = ROWTYPE_AGGREGATEINIT;
        $this->resetAttributes();
        $this->renderRow();
        if (($this->isGridAdd() || $this->isGridEdit())) { // Render template row first
            $this->RowIndex = '$rowindex$';
        }
    }

    // Set up Row
    public function setupRow()
    {
        global $CurrentForm;
        if ($this->isGridAdd() || $this->isGridEdit()) {
            if ($this->RowIndex === '$rowindex$') { // Render template row first
                $this->loadRowValues();

                // Set row properties
                $this->resetAttributes();
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_workstation", "data-rowtype" => ROWTYPE_ADD]);
                $this->RowAttrs->appendClass("ew-template");
                // Render row
                $this->RowType = ROWTYPE_ADD;
                $this->renderRow();

                // Render list options
                $this->renderListOptions();

                // Reset record count for template row
                $this->RecordCount--;
                return;
            }
        }

        // Set up key count
        $this->KeyCount = $this->RowIndex;

        // Init row class and style
        $this->resetAttributes();
        $this->CssClass = "";
        if ($this->isCopy() && $this->InlineRowCount == 0 && !$this->loadRow()) { // Inline copy
            $this->CurrentAction = "add";
        }
        if ($this->isAdd() && $this->InlineRowCount == 0 || $this->isGridAdd()) {
            $this->loadRowValues(); // Load default values
            $this->OldKey = "";
            $this->setKey($this->OldKey);
        } elseif ($this->isInlineInserted() && $this->UseInfiniteScroll) {
            // Nothing to do, just use current values
        } elseif (!($this->isCopy() && $this->InlineRowCount == 0)) {
            $this->loadRowValues($this->Recordset); // Load row values
            if ($this->isGridEdit() || $this->isMultiEdit()) {
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
                $this->setKey($this->OldKey);
            }
        }
        $this->RowType = ROWTYPE_VIEW; // Render view
        if (($this->isAdd() || $this->isCopy()) && $this->InlineRowCount == 0 || $this->isGridAdd()) { // Add
            $this->RowType = ROWTYPE_ADD; // Render add
        }

        // Inline Add/Copy row (row 0)
        if ($this->RowType == ROWTYPE_ADD && ($this->isAdd() || $this->isCopy())) {
            $this->InlineRowCount++;
            $this->RecordCount--; // Reset record count for inline add/copy row
            if ($this->TotalRecords == 0) { // Reset stop record if no records
                $this->StopRecord = 0;
            }
        } else {
            // Inline Edit row
            if ($this->RowType == ROWTYPE_EDIT && $this->isEdit()) {
                $this->InlineRowCount++;
            }
            $this->RowCount++; // Increment row count
        }

        // Set up row attributes
        $this->RowAttrs->merge([
            "data-rowindex" => $this->RowCount,
            "data-key" => $this->getKey(true),
            "id" => "r" . $this->RowCount . "_workstation",
            "data-rowtype" => $this->RowType,
            "class" => ($this->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($this->isAdd() && $this->RowType == ROWTYPE_ADD || $this->isEdit() && $this->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $this->RowAttrs->appendClass("table-active");
        }

        // Render row
        $this->renderRow();

        // Render list options
        $this->renderListOptions();
    }

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // Load query builder rules
        $rules = Post("rules");
        if ($rules && $this->Command == "") {
            $this->QueryRules = $rules;
            $this->Command = "search";
        }

        // Workstation_Name
        if ($this->Workstation_Name->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Workstation_Name->AdvancedSearch->SearchValue != "" || $this->Workstation_Name->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Workstation_Remark
        if ($this->Workstation_Remark->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Workstation_Remark->AdvancedSearch->SearchValue != "" || $this->Workstation_Remark->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // User_Email
        if ($this->User_Email->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->User_Email->AdvancedSearch->SearchValue != "" || $this->User_Email->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // User_Name
        if ($this->User_Name->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->User_Name->AdvancedSearch->SearchValue != "" || $this->User_Name->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // User_Employee_Number
        if ($this->User_Employee_Number->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->User_Employee_Number->AdvancedSearch->SearchValue != "" || $this->User_Employee_Number->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // User_Phone_Number
        if ($this->User_Phone_Number->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->User_Phone_Number->AdvancedSearch->SearchValue != "" || $this->User_Phone_Number->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Address_Name
        if ($this->Address_Name->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Address_Name->AdvancedSearch->SearchValue != "" || $this->Address_Name->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Address_Street
        if ($this->Address_Street->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Address_Street->AdvancedSearch->SearchValue != "" || $this->Address_Street->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Address_Zipcode
        if ($this->Address_Zipcode->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Address_Zipcode->AdvancedSearch->SearchValue != "" || $this->Address_Zipcode->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Address_City
        if ($this->Address_City->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Address_City->AdvancedSearch->SearchValue != "" || $this->Address_City->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Address_Country
        if ($this->Address_Country->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Address_Country->AdvancedSearch->SearchValue != "" || $this->Address_Country->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Type
        if ($this->Component_Type->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Type->AdvancedSearch->SearchValue != "" || $this->Component_Type->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Category
        if ($this->Component_Category->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Category->AdvancedSearch->SearchValue != "" || $this->Component_Category->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Make
        if ($this->Component_Make->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Make->AdvancedSearch->SearchValue != "" || $this->Component_Make->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Model
        if ($this->Component_Model->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Model->AdvancedSearch->SearchValue != "" || $this->Component_Model->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Serial_Number
        if ($this->Component_Serial_Number->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Serial_Number->AdvancedSearch->SearchValue != "" || $this->Component_Serial_Number->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Display_Size
        if ($this->Component_Display_Size->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Display_Size->AdvancedSearch->SearchValue != "" || $this->Component_Display_Size->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Keyboard_Layout
        if ($this->Component_Keyboard_Layout->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Keyboard_Layout->AdvancedSearch->SearchValue != "" || $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Type1
        if ($this->Component_Type1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Type1->AdvancedSearch->SearchValue != "" || $this->Component_Type1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Category1
        if ($this->Component_Category1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Category1->AdvancedSearch->SearchValue != "" || $this->Component_Category1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Make1
        if ($this->Component_Make1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Make1->AdvancedSearch->SearchValue != "" || $this->Component_Make1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Model1
        if ($this->Component_Model1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Model1->AdvancedSearch->SearchValue != "" || $this->Component_Model1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Serial_Number1
        if ($this->Component_Serial_Number1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Serial_Number1->AdvancedSearch->SearchValue != "" || $this->Component_Serial_Number1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Display_Size1
        if ($this->Component_Display_Size1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Display_Size1->AdvancedSearch->SearchValue != "" || $this->Component_Display_Size1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Keyboard_Layout1
        if ($this->Component_Keyboard_Layout1->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue != "" || $this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Type2
        if ($this->Component_Type2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Type2->AdvancedSearch->SearchValue != "" || $this->Component_Type2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Category2
        if ($this->Component_Category2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Category2->AdvancedSearch->SearchValue != "" || $this->Component_Category2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Make2
        if ($this->Component_Make2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Make2->AdvancedSearch->SearchValue != "" || $this->Component_Make2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Model2
        if ($this->Component_Model2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Model2->AdvancedSearch->SearchValue != "" || $this->Component_Model2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Serial_Number2
        if ($this->Component_Serial_Number2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Serial_Number2->AdvancedSearch->SearchValue != "" || $this->Component_Serial_Number2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Display_Size2
        if ($this->Component_Display_Size2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Display_Size2->AdvancedSearch->SearchValue != "" || $this->Component_Display_Size2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // Component_Keyboard_Layout2
        if ($this->Component_Keyboard_Layout2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue != "" || $this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }
        return $hasValue;
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

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn);
            if ($rs && ($row = $rs->fields)) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

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
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // Workstation_Name
            if ($this->Workstation_Name->UseFilter && !EmptyValue($this->Workstation_Name->AdvancedSearch->SearchValue)) {
                if (is_array($this->Workstation_Name->AdvancedSearch->SearchValue)) {
                    $this->Workstation_Name->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Workstation_Name->AdvancedSearch->SearchValue);
                }
                $this->Workstation_Name->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Workstation_Name->AdvancedSearch->SearchValue);
            }

            // Workstation_Remark
            if ($this->Workstation_Remark->UseFilter && !EmptyValue($this->Workstation_Remark->AdvancedSearch->SearchValue)) {
                if (is_array($this->Workstation_Remark->AdvancedSearch->SearchValue)) {
                    $this->Workstation_Remark->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Workstation_Remark->AdvancedSearch->SearchValue);
                }
                $this->Workstation_Remark->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Workstation_Remark->AdvancedSearch->SearchValue);
            }

            // User_Email
            if ($this->User_Email->UseFilter && !EmptyValue($this->User_Email->AdvancedSearch->SearchValue)) {
                if (is_array($this->User_Email->AdvancedSearch->SearchValue)) {
                    $this->User_Email->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Email->AdvancedSearch->SearchValue);
                }
                $this->User_Email->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Email->AdvancedSearch->SearchValue);
            }

            // User_Name
            if ($this->User_Name->UseFilter && !EmptyValue($this->User_Name->AdvancedSearch->SearchValue)) {
                if (is_array($this->User_Name->AdvancedSearch->SearchValue)) {
                    $this->User_Name->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Name->AdvancedSearch->SearchValue);
                }
                $this->User_Name->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Name->AdvancedSearch->SearchValue);
            }

            // User_Employee_Number
            if ($this->User_Employee_Number->UseFilter && !EmptyValue($this->User_Employee_Number->AdvancedSearch->SearchValue)) {
                if (is_array($this->User_Employee_Number->AdvancedSearch->SearchValue)) {
                    $this->User_Employee_Number->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Employee_Number->AdvancedSearch->SearchValue);
                }
                $this->User_Employee_Number->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Employee_Number->AdvancedSearch->SearchValue);
            }

            // User_Phone_Number
            if ($this->User_Phone_Number->UseFilter && !EmptyValue($this->User_Phone_Number->AdvancedSearch->SearchValue)) {
                if (is_array($this->User_Phone_Number->AdvancedSearch->SearchValue)) {
                    $this->User_Phone_Number->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Phone_Number->AdvancedSearch->SearchValue);
                }
                $this->User_Phone_Number->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->User_Phone_Number->AdvancedSearch->SearchValue);
            }

            // Address_Name
            if ($this->Address_Name->UseFilter && !EmptyValue($this->Address_Name->AdvancedSearch->SearchValue)) {
                if (is_array($this->Address_Name->AdvancedSearch->SearchValue)) {
                    $this->Address_Name->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Name->AdvancedSearch->SearchValue);
                }
                $this->Address_Name->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Name->AdvancedSearch->SearchValue);
            }

            // Address_Street
            if ($this->Address_Street->UseFilter && !EmptyValue($this->Address_Street->AdvancedSearch->SearchValue)) {
                if (is_array($this->Address_Street->AdvancedSearch->SearchValue)) {
                    $this->Address_Street->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Street->AdvancedSearch->SearchValue);
                }
                $this->Address_Street->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Street->AdvancedSearch->SearchValue);
            }

            // Address_Zipcode
            if ($this->Address_Zipcode->UseFilter && !EmptyValue($this->Address_Zipcode->AdvancedSearch->SearchValue)) {
                if (is_array($this->Address_Zipcode->AdvancedSearch->SearchValue)) {
                    $this->Address_Zipcode->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Zipcode->AdvancedSearch->SearchValue);
                }
                $this->Address_Zipcode->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Zipcode->AdvancedSearch->SearchValue);
            }

            // Address_City
            if ($this->Address_City->UseFilter && !EmptyValue($this->Address_City->AdvancedSearch->SearchValue)) {
                if (is_array($this->Address_City->AdvancedSearch->SearchValue)) {
                    $this->Address_City->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_City->AdvancedSearch->SearchValue);
                }
                $this->Address_City->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_City->AdvancedSearch->SearchValue);
            }

            // Address_Country
            if ($this->Address_Country->UseFilter && !EmptyValue($this->Address_Country->AdvancedSearch->SearchValue)) {
                if (is_array($this->Address_Country->AdvancedSearch->SearchValue)) {
                    $this->Address_Country->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Country->AdvancedSearch->SearchValue);
                }
                $this->Address_Country->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Address_Country->AdvancedSearch->SearchValue);
            }

            // Component_Type
            if ($this->Component_Type->UseFilter && !EmptyValue($this->Component_Type->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Type->AdvancedSearch->SearchValue)) {
                    $this->Component_Type->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Type->AdvancedSearch->SearchValue);
                }
                $this->Component_Type->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Type->AdvancedSearch->SearchValue);
            }
            $this->Component_Type->setupEditAttributes();
            $curVal = trim(strval($this->Component_Type->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Type->AdvancedSearch->ViewValue2 = $this->Component_Type->lookupCacheOption($curVal);
            } else {
                $this->Component_Type->AdvancedSearch->ViewValue2 = $this->Component_Type->Lookup !== null && is_array($this->Component_Type->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Type->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Type->EditValue2 = array_values($this->Component_Type->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Type->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Type->EditValue2 = $arwrk;
            }
            $this->Component_Type->PlaceHolder = RemoveHtml($this->Component_Type->caption());

            // Component_Category
            if ($this->Component_Category->UseFilter && !EmptyValue($this->Component_Category->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Category->AdvancedSearch->SearchValue)) {
                    $this->Component_Category->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Category->AdvancedSearch->SearchValue);
                }
                $this->Component_Category->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Category->AdvancedSearch->SearchValue);
            }
            $this->Component_Category->setupEditAttributes();
            $curVal = trim(strval($this->Component_Category->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Category->AdvancedSearch->ViewValue2 = $this->Component_Category->lookupCacheOption($curVal);
            } else {
                $this->Component_Category->AdvancedSearch->ViewValue2 = $this->Component_Category->Lookup !== null && is_array($this->Component_Category->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Category->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Category->EditValue2 = array_values($this->Component_Category->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Category->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Category->EditValue2 = $arwrk;
            }
            $this->Component_Category->PlaceHolder = RemoveHtml($this->Component_Category->caption());

            // Component_Make
            if ($this->Component_Make->UseFilter && !EmptyValue($this->Component_Make->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Make->AdvancedSearch->SearchValue)) {
                    $this->Component_Make->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Make->AdvancedSearch->SearchValue);
                }
                $this->Component_Make->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Make->AdvancedSearch->SearchValue);
            }
            $this->Component_Make->setupEditAttributes();
            $curVal = trim(strval($this->Component_Make->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Make->AdvancedSearch->ViewValue2 = $this->Component_Make->lookupCacheOption($curVal);
            } else {
                $this->Component_Make->AdvancedSearch->ViewValue2 = $this->Component_Make->Lookup !== null && is_array($this->Component_Make->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Make->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Make->EditValue2 = array_values($this->Component_Make->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Make->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Make->EditValue2 = $arwrk;
            }
            $this->Component_Make->PlaceHolder = RemoveHtml($this->Component_Make->caption());

            // Component_Model
            if ($this->Component_Model->UseFilter && !EmptyValue($this->Component_Model->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Model->AdvancedSearch->SearchValue)) {
                    $this->Component_Model->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Model->AdvancedSearch->SearchValue);
                }
                $this->Component_Model->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Model->AdvancedSearch->SearchValue);
            }
            $this->Component_Model->setupEditAttributes();
            $curVal = trim(strval($this->Component_Model->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Model->AdvancedSearch->ViewValue2 = $this->Component_Model->lookupCacheOption($curVal);
            } else {
                $this->Component_Model->AdvancedSearch->ViewValue2 = $this->Component_Model->Lookup !== null && is_array($this->Component_Model->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Model->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Model->EditValue2 = array_values($this->Component_Model->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Model->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Model->EditValue2 = $arwrk;
            }
            $this->Component_Model->PlaceHolder = RemoveHtml($this->Component_Model->caption());

            // Component_Serial_Number
            if ($this->Component_Serial_Number->UseFilter && !EmptyValue($this->Component_Serial_Number->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Serial_Number->AdvancedSearch->SearchValue)) {
                    $this->Component_Serial_Number->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Serial_Number->AdvancedSearch->SearchValue);
                }
                $this->Component_Serial_Number->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Serial_Number->AdvancedSearch->SearchValue);
            }

            // Component_Display_Size
            if ($this->Component_Display_Size->UseFilter && !EmptyValue($this->Component_Display_Size->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Display_Size->AdvancedSearch->SearchValue)) {
                    $this->Component_Display_Size->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Display_Size->AdvancedSearch->SearchValue);
                }
                $this->Component_Display_Size->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Display_Size->AdvancedSearch->SearchValue);
            }
            $this->Component_Display_Size->setupEditAttributes();
            if (!$this->Component_Display_Size->Raw) {
                $this->Component_Display_Size->AdvancedSearch->SearchValue2 = HtmlDecode($this->Component_Display_Size->AdvancedSearch->SearchValue2);
            }
            $this->Component_Display_Size->EditValue2 = HtmlEncode($this->Component_Display_Size->AdvancedSearch->SearchValue2);
            $this->Component_Display_Size->PlaceHolder = RemoveHtml($this->Component_Display_Size->caption());

            // Component_Keyboard_Layout
            if ($this->Component_Keyboard_Layout->UseFilter && !EmptyValue($this->Component_Keyboard_Layout->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Keyboard_Layout->AdvancedSearch->SearchValue)) {
                    $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue);
                }
                $this->Component_Keyboard_Layout->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue);
            }
            $this->Component_Keyboard_Layout->setupEditAttributes();
            if (!$this->Component_Keyboard_Layout->Raw) {
                $this->Component_Keyboard_Layout->AdvancedSearch->SearchValue2 = HtmlDecode($this->Component_Keyboard_Layout->AdvancedSearch->SearchValue2);
            }
            $this->Component_Keyboard_Layout->EditValue2 = HtmlEncode($this->Component_Keyboard_Layout->AdvancedSearch->SearchValue2);
            $this->Component_Keyboard_Layout->PlaceHolder = RemoveHtml($this->Component_Keyboard_Layout->caption());

            // Component_Type1
            if ($this->Component_Type1->UseFilter && !EmptyValue($this->Component_Type1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Type1->AdvancedSearch->SearchValue)) {
                    $this->Component_Type1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Type1->AdvancedSearch->SearchValue);
                }
                $this->Component_Type1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Type1->AdvancedSearch->SearchValue);
            }
            $this->Component_Type1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Type1->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Type1->AdvancedSearch->ViewValue2 = $this->Component_Type1->lookupCacheOption($curVal);
            } else {
                $this->Component_Type1->AdvancedSearch->ViewValue2 = $this->Component_Type1->Lookup !== null && is_array($this->Component_Type1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Type1->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Type1->EditValue2 = array_values($this->Component_Type1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Type1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Type1->EditValue2 = $arwrk;
            }
            $this->Component_Type1->PlaceHolder = RemoveHtml($this->Component_Type1->caption());

            // Component_Category1
            if ($this->Component_Category1->UseFilter && !EmptyValue($this->Component_Category1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Category1->AdvancedSearch->SearchValue)) {
                    $this->Component_Category1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Category1->AdvancedSearch->SearchValue);
                }
                $this->Component_Category1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Category1->AdvancedSearch->SearchValue);
            }
            $this->Component_Category1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Category1->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Category1->AdvancedSearch->ViewValue2 = $this->Component_Category1->lookupCacheOption($curVal);
            } else {
                $this->Component_Category1->AdvancedSearch->ViewValue2 = $this->Component_Category1->Lookup !== null && is_array($this->Component_Category1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Category1->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Category1->EditValue2 = array_values($this->Component_Category1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Category1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Category1->EditValue2 = $arwrk;
            }
            $this->Component_Category1->PlaceHolder = RemoveHtml($this->Component_Category1->caption());

            // Component_Make1
            if ($this->Component_Make1->UseFilter && !EmptyValue($this->Component_Make1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Make1->AdvancedSearch->SearchValue)) {
                    $this->Component_Make1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Make1->AdvancedSearch->SearchValue);
                }
                $this->Component_Make1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Make1->AdvancedSearch->SearchValue);
            }
            $this->Component_Make1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Make1->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Make1->AdvancedSearch->ViewValue2 = $this->Component_Make1->lookupCacheOption($curVal);
            } else {
                $this->Component_Make1->AdvancedSearch->ViewValue2 = $this->Component_Make1->Lookup !== null && is_array($this->Component_Make1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Make1->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Make1->EditValue2 = array_values($this->Component_Make1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Make1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Make1->EditValue2 = $arwrk;
            }
            $this->Component_Make1->PlaceHolder = RemoveHtml($this->Component_Make1->caption());

            // Component_Model1
            if ($this->Component_Model1->UseFilter && !EmptyValue($this->Component_Model1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Model1->AdvancedSearch->SearchValue)) {
                    $this->Component_Model1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Model1->AdvancedSearch->SearchValue);
                }
                $this->Component_Model1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Model1->AdvancedSearch->SearchValue);
            }
            $this->Component_Model1->setupEditAttributes();
            if (!$this->Component_Model1->Raw) {
                $this->Component_Model1->AdvancedSearch->SearchValue2 = HtmlDecode($this->Component_Model1->AdvancedSearch->SearchValue2);
            }
            $this->Component_Model1->EditValue2 = HtmlEncode($this->Component_Model1->AdvancedSearch->SearchValue2);
            $this->Component_Model1->PlaceHolder = RemoveHtml($this->Component_Model1->caption());

            // Component_Serial_Number1
            if ($this->Component_Serial_Number1->UseFilter && !EmptyValue($this->Component_Serial_Number1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Serial_Number1->AdvancedSearch->SearchValue)) {
                    $this->Component_Serial_Number1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Serial_Number1->AdvancedSearch->SearchValue);
                }
                $this->Component_Serial_Number1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Serial_Number1->AdvancedSearch->SearchValue);
            }

            // Component_Display_Size1
            if ($this->Component_Display_Size1->UseFilter && !EmptyValue($this->Component_Display_Size1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Display_Size1->AdvancedSearch->SearchValue)) {
                    $this->Component_Display_Size1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Display_Size1->AdvancedSearch->SearchValue);
                }
                $this->Component_Display_Size1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Display_Size1->AdvancedSearch->SearchValue);
            }
            $this->Component_Display_Size1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Display_Size1->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Display_Size1->AdvancedSearch->ViewValue2 = $this->Component_Display_Size1->lookupCacheOption($curVal);
            } else {
                $this->Component_Display_Size1->AdvancedSearch->ViewValue2 = $this->Component_Display_Size1->Lookup !== null && is_array($this->Component_Display_Size1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Display_Size1->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Display_Size1->EditValue2 = array_values($this->Component_Display_Size1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Display_Size1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Display_Size1->EditValue2 = $arwrk;
            }
            $this->Component_Display_Size1->PlaceHolder = RemoveHtml($this->Component_Display_Size1->caption());

            // Component_Keyboard_Layout1
            if ($this->Component_Keyboard_Layout1->UseFilter && !EmptyValue($this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue)) {
                    $this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue);
                }
                $this->Component_Keyboard_Layout1->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue);
            }
            $this->Component_Keyboard_Layout1->setupEditAttributes();
            $curVal = trim(strval($this->Component_Keyboard_Layout1->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Keyboard_Layout1->AdvancedSearch->ViewValue2 = $this->Component_Keyboard_Layout1->lookupCacheOption($curVal);
            } else {
                $this->Component_Keyboard_Layout1->AdvancedSearch->ViewValue2 = $this->Component_Keyboard_Layout1->Lookup !== null && is_array($this->Component_Keyboard_Layout1->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Keyboard_Layout1->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Keyboard_Layout1->EditValue2 = array_values($this->Component_Keyboard_Layout1->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Keyboard_Layout1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Keyboard_Layout1->EditValue2 = $arwrk;
            }
            $this->Component_Keyboard_Layout1->PlaceHolder = RemoveHtml($this->Component_Keyboard_Layout1->caption());

            // Component_Type2
            if ($this->Component_Type2->UseFilter && !EmptyValue($this->Component_Type2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Type2->AdvancedSearch->SearchValue)) {
                    $this->Component_Type2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Type2->AdvancedSearch->SearchValue);
                }
                $this->Component_Type2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Type2->AdvancedSearch->SearchValue);
            }
            $this->Component_Type2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Type2->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Type2->AdvancedSearch->ViewValue2 = $this->Component_Type2->lookupCacheOption($curVal);
            } else {
                $this->Component_Type2->AdvancedSearch->ViewValue2 = $this->Component_Type2->Lookup !== null && is_array($this->Component_Type2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Type2->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Type2->EditValue2 = array_values($this->Component_Type2->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Type2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Type2->EditValue2 = $arwrk;
            }
            $this->Component_Type2->PlaceHolder = RemoveHtml($this->Component_Type2->caption());

            // Component_Category2
            if ($this->Component_Category2->UseFilter && !EmptyValue($this->Component_Category2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Category2->AdvancedSearch->SearchValue)) {
                    $this->Component_Category2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Category2->AdvancedSearch->SearchValue);
                }
                $this->Component_Category2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Category2->AdvancedSearch->SearchValue);
            }
            $this->Component_Category2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Category2->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Category2->AdvancedSearch->ViewValue2 = $this->Component_Category2->lookupCacheOption($curVal);
            } else {
                $this->Component_Category2->AdvancedSearch->ViewValue2 = $this->Component_Category2->Lookup !== null && is_array($this->Component_Category2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Category2->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Category2->EditValue2 = array_values($this->Component_Category2->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Category2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Category2->EditValue2 = $arwrk;
            }
            $this->Component_Category2->PlaceHolder = RemoveHtml($this->Component_Category2->caption());

            // Component_Make2
            if ($this->Component_Make2->UseFilter && !EmptyValue($this->Component_Make2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Make2->AdvancedSearch->SearchValue)) {
                    $this->Component_Make2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Make2->AdvancedSearch->SearchValue);
                }
                $this->Component_Make2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Make2->AdvancedSearch->SearchValue);
            }
            $this->Component_Make2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Make2->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Make2->AdvancedSearch->ViewValue2 = $this->Component_Make2->lookupCacheOption($curVal);
            } else {
                $this->Component_Make2->AdvancedSearch->ViewValue2 = $this->Component_Make2->Lookup !== null && is_array($this->Component_Make2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Make2->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Make2->EditValue2 = array_values($this->Component_Make2->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Make2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Make2->EditValue2 = $arwrk;
            }
            $this->Component_Make2->PlaceHolder = RemoveHtml($this->Component_Make2->caption());

            // Component_Model2
            if ($this->Component_Model2->UseFilter && !EmptyValue($this->Component_Model2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Model2->AdvancedSearch->SearchValue)) {
                    $this->Component_Model2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Model2->AdvancedSearch->SearchValue);
                }
                $this->Component_Model2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Model2->AdvancedSearch->SearchValue);
            }
            $this->Component_Model2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Model2->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Model2->AdvancedSearch->ViewValue2 = $this->Component_Model2->lookupCacheOption($curVal);
            } else {
                $this->Component_Model2->AdvancedSearch->ViewValue2 = $this->Component_Model2->Lookup !== null && is_array($this->Component_Model2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Model2->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Model2->EditValue2 = array_values($this->Component_Model2->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Model2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Model2->EditValue2 = $arwrk;
            }
            $this->Component_Model2->PlaceHolder = RemoveHtml($this->Component_Model2->caption());

            // Component_Serial_Number2
            if ($this->Component_Serial_Number2->UseFilter && !EmptyValue($this->Component_Serial_Number2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Serial_Number2->AdvancedSearch->SearchValue)) {
                    $this->Component_Serial_Number2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Serial_Number2->AdvancedSearch->SearchValue);
                }
                $this->Component_Serial_Number2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Serial_Number2->AdvancedSearch->SearchValue);
            }

            // Component_Display_Size2
            if ($this->Component_Display_Size2->UseFilter && !EmptyValue($this->Component_Display_Size2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Display_Size2->AdvancedSearch->SearchValue)) {
                    $this->Component_Display_Size2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Display_Size2->AdvancedSearch->SearchValue);
                }
                $this->Component_Display_Size2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Display_Size2->AdvancedSearch->SearchValue);
            }
            $this->Component_Display_Size2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Display_Size2->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Display_Size2->AdvancedSearch->ViewValue2 = $this->Component_Display_Size2->lookupCacheOption($curVal);
            } else {
                $this->Component_Display_Size2->AdvancedSearch->ViewValue2 = $this->Component_Display_Size2->Lookup !== null && is_array($this->Component_Display_Size2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Display_Size2->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Display_Size2->EditValue2 = array_values($this->Component_Display_Size2->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Display_Size2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Display_Size2->EditValue2 = $arwrk;
            }
            $this->Component_Display_Size2->PlaceHolder = RemoveHtml($this->Component_Display_Size2->caption());

            // Component_Keyboard_Layout2
            if ($this->Component_Keyboard_Layout2->UseFilter && !EmptyValue($this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue)) {
                if (is_array($this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue)) {
                    $this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue);
                }
                $this->Component_Keyboard_Layout2->EditValue = explode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue);
            }
            $this->Component_Keyboard_Layout2->setupEditAttributes();
            $curVal = trim(strval($this->Component_Keyboard_Layout2->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->Component_Keyboard_Layout2->AdvancedSearch->ViewValue2 = $this->Component_Keyboard_Layout2->lookupCacheOption($curVal);
            } else {
                $this->Component_Keyboard_Layout2->AdvancedSearch->ViewValue2 = $this->Component_Keyboard_Layout2->Lookup !== null && is_array($this->Component_Keyboard_Layout2->lookupOptions()) ? $curVal : null;
            }
            if ($this->Component_Keyboard_Layout2->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->Component_Keyboard_Layout2->EditValue2 = array_values($this->Component_Keyboard_Layout2->lookupOptions());
            } else { // Lookup from database
                $filterWrk = "";
                $sqlWrk = $this->Component_Keyboard_Layout2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Component_Keyboard_Layout2->EditValue2 = $arwrk;
            }
            $this->Component_Keyboard_Layout2->PlaceHolder = RemoveHtml($this->Component_Keyboard_Layout2->caption());
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    /**
     * Import file
     *
     * @param string $filetoken File token to locate the uploaded import file
     * @param bool $rollback Try import and then rollback
     * @return bool
     */
    public function import($filetoken, $rollback = false)
    {
        global $Security, $Language;

        // Check if valid token
        if (EmptyValue($filetoken)) {
            return false;
        }

        // Get uploaded files by token
        $files = GetUploadedFileNames($filetoken);
        $exts = explode(",", Config("IMPORT_FILE_ALLOWED_EXTENSIONS"));
        $result = [Config("API_FILE_TOKEN_NAME") => $filetoken, "files" => []];

        // Set header
        if (ob_get_length()) {
            ob_clean();
        }
        header("Cache-Control: no-store");
        header("Content-Type: text/event-stream");

        // Import records
        try {
            foreach ($files as $file) {
                $res = ["file" => basename($file)];
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                // Ignore log file
                if ($ext == "txt") {
                    continue;
                }

                // Check file extension
                if (!in_array($ext, $exts)) {
                    $res = array_merge($res, ["error" => str_replace("%e", $ext, $Language->phrase("ImportInvalidFileExtension"))]);
                    SendEvent($res, "error");
                    return false;
                }

                // Set up options
                $options = [
                    "file" => $file,
                    "inputEncoding" => "", // For CSV only
                    "delimiter" => ",", // For CSV only
                    "enclosure" => "\"", // For CSV only
                    "escape" => "\\", // For CSV only
                    "activeSheet" => null, // For PhpSpreadsheet only
                    "readOnly" => true, // For PhpSpreadsheet only
                    "maxRows" => null, // For PhpSpreadsheet only
                    "headerRowNumber" => 0,
                    "headers" => [],
                    "offset" => 0,
                    "limit" => null,
                ];
                foreach ($_GET as $key => $value) {
                    if (!in_array($key, [Config("API_ACTION_NAME"), Config("API_FILE_TOKEN_NAME")])) {
                        $options[$key] = $value;
                    }
                }

                // Workflow builder
                $builder = fn($workflow) => $workflow;

                // Call Page Importing server event
                if (!$this->pageImporting($builder, $options)) {
                    SendEvent($res, "error");
                    return false;
                }

                // Set max execution time
                if (Config("IMPORT_MAX_EXECUTION_TIME") > 0) {
                    ini_set("max_execution_time", Config("IMPORT_MAX_EXECUTION_TIME"));
                }

                // Reader
                try {
                    if ($ext == "csv") {
                        $csv = file_get_contents($file);
                        if ($csv !== false) {
                            if (StartsString("\xEF\xBB\xBF", $csv)) { // UTF-8 BOM
                                $csv = substr($csv, 3);
                            } elseif ($options["inputEncoding"] != "" && !SameText($options["inputEncoding"], "UTF-8")) {
                                $csv = Convert($options["inputEncoding"], "UTF-8", $csv);
                            }
                            file_put_contents($file, $csv);
                        }
                        $reader = new \Port\Csv\CsvReader(new \SplFileObject($file), $options["delimiter"], $options["enclosure"], $options["escape"]);
                    } else {
                        $reader = new \Port\Spreadsheet\SpreadsheetReader(new \SplFileObject($file), $options["headerRowNumber"], $options["activeSheet"], $options["readOnly"], $options["maxRows"]);
                    }
                    if (is_array($options["headers"]) && count($options["headers"]) > 0) {
                        $reader->setColumnHeaders($options["headers"]);
                    } elseif (is_int($options["headerRowNumber"])) {
                        $reader->setHeaderRowNumber($options["headerRowNumber"]);
                    }
                } catch (\Exception $e) {
                    $res = array_merge($res, ["error" => $e->getMessage()]);
                    SendEvent($res, "error");
                    return false;
                }

                // Column headers
                $headers = $reader->getColumnHeaders();
                if (count($headers) == 0) { // Missing headers
                    $res["error"] = $Language->phrase("ImportNoHeaderRow");
                    SendEvent($res, "error");
                    return false;
                }

                // Counts
                $recordCnt = $reader->count();
                if ($options["offset"] > 0) {
                    $recordCnt -= $options["offset"];
                    if ($options["limit"] > 0) {
                        $recordCnt = min($recordCnt, $options["limit"]);
                    }
                    if ($recordCnt < 0) {
                        $recordCnt = 0;
                    }
                }
                $cnt = 0;
                $successCnt = 0;
                $failCnt = 0;
                $res = array_merge($res, ["totalCount" => $recordCnt, "count" => $cnt, "successCount" => 0, "failCount" => 0]);

                // Writer
                $writer = new \Port\Writer\CallbackWriter(function ($row) use (&$res, &$cnt, &$successCnt, &$failCnt) {
                    try {
                        $success = $this->importRow($row, ++$cnt); // Import row
                        if ($success) {
                            $successCnt++;
                        } else {
                            $failCnt++;
                        }
                        $err = "";
                    } catch (\Port\Exception $e) { // Catch exception so the workflow continues
                        $failCnt++;
                        $err = $e->getMessage();
                        if ($failCnt > $this->ImportMaxFailures) {
                            throw $e; // Throw \Port\Exception to terminate the workflow
                        }
                    } finally {
                        $res = array_merge($res, [
                            "row" => $row, // Current row
                            "success" => $success, // For current row
                            "error" => $err, // For current row
                            "count" => $cnt,
                            "successCount" => $successCnt,
                            "failCount" => $failCnt
                        ]);
                        SendEvent($res);
                    }
                });

                // Connection
                $conn = $this->getConnection();

                // Begin transaction
                if ($this->ImportUseTransaction) {
                    $conn->beginTransaction();
                }

                // Workflow
                $workflow = new \Port\Steps\StepAggregator($reader);
                $workflow->setLogger(Logger());
                $workflow->setSkipItemOnFailure(false); // Stop on exception
                $workflow = $builder($workflow);

                // Filter step
                $step = new \Port\Steps\Step\FilterStep();
                $step->add(new \Port\Filter\OffsetFilter($options["offset"], $options["limit"]));
                try {
                    $info = @$workflow->addWriter($writer)->addStep($step)->process();
                } finally {
                    // Rollback transaction
                    if ($this->ImportUseTransaction) {
                        if ($rollback || $failCnt > $this->ImportMaxFailures) {
                            $res["rollbacked"] = $conn->rollback();
                        } else {
                            $conn->commit();
                        }
                    }
                    unset($res["row"], $res["error"]); // Remove current row info
                    $res["success"] = $cnt > 0 && $failCnt <= $this->ImportMaxFailures; // Set success status of current file
                    SendEvent($res); // Current file imported
                    $result["files"][] = $res;

                    // Call Page Imported server event
                    $this->pageImported($info, $res);
                }
            }
        } finally {
            $result["failCount"] = array_reduce($result["files"], fn($carry, $item) => $carry + $item["failCount"], 0); // For client side
            $result["success"] = array_reduce($result["files"], fn($carry, $item) => $carry && $item["success"], true); // All files successful
            $result["rollbacked"] = array_reduce($result["files"], fn($carry, $item) => $carry && $item["success"] && ($item["rollbacked"] ?? false), true); // All file rollbacked successfully
            if ($result["success"] && !$result["rollbacked"]) {
                CleanUploadTempPaths($filetoken);
            }
            SendEvent($result, "complete"); // All files imported
            return $result["success"];
        }
    }

    /**
     * Import a row
     *
     * @param array $row Row to be imported
     * @param int $cnt Index of the row (1-based)
     * @return bool
     */
    protected function importRow(&$row, $cnt)
    {
        global $Language;

        // Call Row Import server event
        if (!$this->rowImport($row, $cnt)) {
            return false;
        }

        // Check field names and values
        foreach ($row as $name => $value) {
            $fld = $this->Fields[$name];
            if (!$fld) {
                throw new \Port\Exception\UnexpectedValueException(str_replace("%f", $name, $Language->phrase("ImportInvalidFieldName")));
            }
            if (!$this->checkValue($fld, $value)) {
                throw new \Port\Exception\UnexpectedValueException(str_replace(["%f", "%v"], [$name, $value], $Language->phrase("ImportInvalidFieldValue")));
            }
        }

        // Insert/Update to database
        $res = false;
        if (!$this->ImportInsertOnly && $oldrow = $this->load($row)) {
            if (!method_exists($this, "rowUpdating") || $this->rowUpdating($oldrow, $row)) {
                if ($res = $this->update($row, "", $oldrow)) {
                    if (method_exists($this, "rowUpdated")) {
                        $this->rowUpdated($oldrow, $row);
                    }
                }
            }
        } else {
            if (!method_exists($this, "rowInserting") || $this->rowInserting(null, $row)) {
                if ($res = $this->insert($row)) {
                    if (method_exists($this, "rowInserted")) {
                        $this->rowInserted(null, $row);
                    }
                }
            }
        }
        return $res;
    }

    /**
     * Check field value
     *
     * @param object $fld Field object
     * @param object $value
     * @return bool
     */
    protected function checkValue($fld, $value)
    {
        if ($fld->DataType == DATATYPE_NUMBER && !is_numeric($value)) {
            return false;
        } elseif ($fld->DataType == DATATYPE_DATE && !CheckDate($value, $fld->formatPattern())) {
            return false;
        }
        return true;
    }

    // Load row
    protected function load($row)
    {
        $filter = $this->getRecordFilter($row);
        if (!$filter) {
            return null;
        }
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        return $conn->fetchAssociative($sql);
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->Workstation_Name->AdvancedSearch->load();
        $this->Workstation_Remark->AdvancedSearch->load();
        $this->User_Email->AdvancedSearch->load();
        $this->User_Name->AdvancedSearch->load();
        $this->User_Employee_Number->AdvancedSearch->load();
        $this->User_Phone_Number->AdvancedSearch->load();
        $this->Address_Name->AdvancedSearch->load();
        $this->Address_Street->AdvancedSearch->load();
        $this->Address_Zipcode->AdvancedSearch->load();
        $this->Address_City->AdvancedSearch->load();
        $this->Address_Country->AdvancedSearch->load();
        $this->Component_Type->AdvancedSearch->load();
        $this->Component_Category->AdvancedSearch->load();
        $this->Component_Make->AdvancedSearch->load();
        $this->Component_Model->AdvancedSearch->load();
        $this->Component_Serial_Number->AdvancedSearch->load();
        $this->Component_Display_Size->AdvancedSearch->load();
        $this->Component_Keyboard_Layout->AdvancedSearch->load();
        $this->Component_Type1->AdvancedSearch->load();
        $this->Component_Category1->AdvancedSearch->load();
        $this->Component_Make1->AdvancedSearch->load();
        $this->Component_Model1->AdvancedSearch->load();
        $this->Component_Serial_Number1->AdvancedSearch->load();
        $this->Component_Display_Size1->AdvancedSearch->load();
        $this->Component_Keyboard_Layout1->AdvancedSearch->load();
        $this->Component_Type2->AdvancedSearch->load();
        $this->Component_Category2->AdvancedSearch->load();
        $this->Component_Make2->AdvancedSearch->load();
        $this->Component_Model2->AdvancedSearch->load();
        $this->Component_Serial_Number2->AdvancedSearch->load();
        $this->Component_Display_Size2->AdvancedSearch->load();
        $this->Component_Keyboard_Layout2->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        if ($type == "print" || $custom) { // Printer friendly / custom export
            $pageUrl = $this->pageUrl(false);
            $exportUrl = GetUrl($pageUrl . "export=" . $type . ($custom ? "&amp;custom=1" : ""));
        } else { // Export API URL
            $exportUrl = GetApiUrl(Config("API_EXPORT_ACTION") . "/" . $type . "/" . $this->TableVar);
        }
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" form=\"fworkstationlist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" form=\"fworkstationlist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" form=\"fworkstationlist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\">" . $Language->phrase("ExportToPdf") . "</a>";
            }
        } elseif (SameText($type, "html")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-html\" title=\"" . HtmlEncode($Language->phrase("ExportToHtml", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToHtml", true)) . "\">" . $Language->phrase("ExportToHtml") . "</a>";
        } elseif (SameText($type, "xml")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-xml\" title=\"" . HtmlEncode($Language->phrase("ExportToXml", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToXml", true)) . "\">" . $Language->phrase("ExportToXml") . "</a>";
        } elseif (SameText($type, "csv")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-csv\" title=\"" . HtmlEncode($Language->phrase("ExportToCsv", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToCsv", true)) . "\">" . $Language->phrase("ExportToCsv") . "</a>";
        } elseif (SameText($type, "email")) {
            $url = $custom ? ' data-url="' . $exportUrl . '"' : '';
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmail", true) . '" data-caption="' . $Language->phrase("ExportToEmail", true) . '" form="fworkstationlist" data-ew-action="email" data-custom="false" data-hdr="' . $Language->phrase("ExportToEmail", true) . '" data-exported-selected="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("PrinterFriendly", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("PrinterFriendly", true)) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language, $Security;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = false;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel");
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word");
        $item->Visible = false;

        // Export to HTML
        $item = &$this->ExportOptions->add("html");
        $item->Body = $this->getExportTag("html");
        $item->Visible = false;

        // Export to XML
        $item = &$this->ExportOptions->add("xml");
        $item->Body = $this->getExportTag("xml");
        $item->Visible = false;

        // Export to CSV
        $item = &$this->ExportOptions->add("csv");
        $item->Body = $this->getExportTag("csv");
        $item->Visible = false;

        // Export to PDF
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = false;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email");
        $item->Visible = false;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = true;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl(false);
        $this->SearchOptions = new ListOptions(["TagClassName" => "ew-search-option"]);

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : " active";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fworkstationsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction && $this->CurrentAction != "search") {
            $this->SearchOptions->hideAllOptions();
        }
    }

    // Check if any search fields
    public function hasSearchFields()
    {
        return true;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    // Set up import options
    protected function setupImportOptions()
    {
        global $Security, $Language;

        // Import
        $item = &$this->ImportOptions->add("import");
        $item->Body = "<a class=\"ew-import-link ew-import\" role=\"button\" title=\"" . $Language->phrase("Import", true) . "\" data-caption=\"" . $Language->phrase("Import", true) . "\" data-ew-action=\"import\" data-hdr=\"" . $Language->phrase("Import", true) . "\">" . $Language->phrase("Import") . "</a>";
        $item->Visible = $Security->canImport();
        $this->ImportOptions->UseButtonGroup = true;
        $this->ImportOptions->UseDropDownButton = false;
        $this->ImportOptions->DropDownButtonPhrase = $Language->phrase("Import");

        // Add group option item
        $item = &$this->ImportOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    /**
    * Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
    *
    * @param bool $return Return the data rather than output it
    * @return mixed
    */
    public function exportData($doc)
    {
        global $Language;
        $utf8 = SameText(Config("PROJECT_CHARSET"), "utf-8");

        // Load recordset
        $this->TotalRecords = $this->listRecordCount();
        $this->StartRecord = 1;

        // Export all
        if ($this->ExportAll) {
            if (Config("EXPORT_ALL_TIME_LIMIT") >= 0) {
                @set_time_limit(Config("EXPORT_ALL_TIME_LIMIT"));
            }
            $this->DisplayRecords = $this->TotalRecords;
            $this->StopRecord = $this->TotalRecords;
        } else { // Export one page only
            $this->setupStartRecord(); // Set up start record position
            // Set the last record to display
            if ($this->DisplayRecords <= 0) {
                $this->StopRecord = $this->TotalRecords;
            } else {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            }
        }
        $rs = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords);
        if (!$rs || !$doc) {
            RemoveHeader("Content-Type"); // Remove header
            RemoveHeader("Content-Disposition");
            $this->showMessage();
            return;
        }
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords;

        // Call Page Exporting server event
        $doc->ExportCustom = !$this->pageExporting($doc);

        // Page header
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        $doc->Text .= $header;
        $this->exportDocument($doc, $rs, $this->StartRecord, $this->StopRecord, "");

        // Close recordset
        $rs->close();

        // Page footer
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        $doc->Text .= $footer;

        // Export header and footer
        $doc->exportHeaderAndFooter();

        // Call Page Exported server event
        $this->pageExported($doc);
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset(all)
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = ConvertToBool(Param("infinitescroll"));
        if ($pageNo !== null) { // Check for "pageno" parameter first
            $pageNo = ParseInteger($pageNo);
            if (is_numeric($pageNo)) {
                $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                if ($this->StartRecord <= 0) {
                    $this->StartRecord = 1;
                } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                    $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                }
            }
        } elseif ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
            $this->StartRecord = $startRec;
        } elseif (!$infiniteScroll) {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
    }

    // Page Exporting event
    // $doc = export object
    public function pageExporting(&$doc)
    {
        //$doc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $doc = export document object
    public function rowExport($doc, $rs)
    {
        //$doc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $doc = export document object
    public function pageExported($doc)
    {
        //$doc->Text .= "my footer"; // Export footer
        //Log($doc->Text);
    }

    // Page Importing event
    public function pageImporting(&$builder, &$options)
    {
        //var_dump($options); // Show all options for importing
        //$builder = fn($workflow) => $workflow->addStep($myStep);
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($obj, $results)
    {
        //var_dump($obj); // Workflow result object
        //var_dump($results); // Import results
    }
}
