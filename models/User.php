<?php

namespace WorkStationDB\project3;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for user
 */
class User extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = true;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $id;
    public $User_Email;
    public $User_Name;
    public $User_Employee_Number;
    public $User_Phone_Number;
    public $Address_Name;
    public $Address_Street;
    public $Address_Zipcode;
    public $Address_City;
    public $Address_Country;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("language");
        $this->TableVar = "user";
        $this->TableName = 'user';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "`user`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UseColumnVisibility = true;
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // id
        $this->id = new DbField(
            $this, // Table
            'x_id', // Variable name
            'id', // Name
            '`id`', // Expression
            '`id`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->id->InputTextType = "text";
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = false; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id'] = &$this->id;

        // User_Email
        $this->User_Email = new DbField(
            $this, // Table
            'x_User_Email', // Variable name
            'User_Email', // Name
            '`User_Email`', // Expression
            '`User_Email`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`User_Email`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->User_Email->InputTextType = "text";
        $this->User_Email->Required = true; // Required field
        $this->User_Email->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->User_Email->Lookup = new Lookup('User_Email', 'user', true, 'User_Email', ["User_Email","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->User_Email->Lookup = new Lookup('User_Email', 'user', true, 'User_Email', ["User_Email","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->User_Email->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['User_Email'] = &$this->User_Email;

        // User_Name
        $this->User_Name = new DbField(
            $this, // Table
            'x_User_Name', // Variable name
            'User_Name', // Name
            '`User_Name`', // Expression
            '`User_Name`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`User_Name`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->User_Name->InputTextType = "text";
        $this->User_Name->Required = true; // Required field
        $this->User_Name->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->User_Name->Lookup = new Lookup('User_Name', 'user', true, 'User_Name', ["User_Name","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->User_Name->Lookup = new Lookup('User_Name', 'user', true, 'User_Name', ["User_Name","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->User_Name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['User_Name'] = &$this->User_Name;

        // User_Employee_Number
        $this->User_Employee_Number = new DbField(
            $this, // Table
            'x_User_Employee_Number', // Variable name
            'User_Employee_Number', // Name
            '`User_Employee_Number`', // Expression
            '`User_Employee_Number`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`User_Employee_Number`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->User_Employee_Number->InputTextType = "text";
        $this->User_Employee_Number->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->User_Employee_Number->Lookup = new Lookup('User_Employee_Number', 'user', true, 'User_Employee_Number', ["User_Employee_Number","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->User_Employee_Number->Lookup = new Lookup('User_Employee_Number', 'user', true, 'User_Employee_Number', ["User_Employee_Number","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->User_Employee_Number->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['User_Employee_Number'] = &$this->User_Employee_Number;

        // User_Phone_Number
        $this->User_Phone_Number = new DbField(
            $this, // Table
            'x_User_Phone_Number', // Variable name
            'User_Phone_Number', // Name
            '`User_Phone_Number`', // Expression
            '`User_Phone_Number`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`User_Phone_Number`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->User_Phone_Number->InputTextType = "text";
        $this->User_Phone_Number->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->User_Phone_Number->Lookup = new Lookup('User_Phone_Number', 'user', true, 'User_Phone_Number', ["User_Phone_Number","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->User_Phone_Number->Lookup = new Lookup('User_Phone_Number', 'user', true, 'User_Phone_Number', ["User_Phone_Number","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->User_Phone_Number->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['User_Phone_Number'] = &$this->User_Phone_Number;

        // Address_Name
        $this->Address_Name = new DbField(
            $this, // Table
            'x_Address_Name', // Variable name
            'Address_Name', // Name
            '`Address_Name`', // Expression
            '`Address_Name`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Address_Name`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Address_Name->InputTextType = "text";
        $this->Address_Name->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->Address_Name->Lookup = new Lookup('Address_Name', 'user', true, 'Address_Name', ["Address_Name","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->Address_Name->Lookup = new Lookup('Address_Name', 'user', true, 'Address_Name', ["Address_Name","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->Address_Name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Address_Name'] = &$this->Address_Name;

        // Address_Street
        $this->Address_Street = new DbField(
            $this, // Table
            'x_Address_Street', // Variable name
            'Address_Street', // Name
            '`Address_Street`', // Expression
            '`Address_Street`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Address_Street`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Address_Street->InputTextType = "text";
        $this->Address_Street->Required = true; // Required field
        $this->Address_Street->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->Address_Street->Lookup = new Lookup('Address_Street', 'user', true, 'Address_Street', ["Address_Street","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->Address_Street->Lookup = new Lookup('Address_Street', 'user', true, 'Address_Street', ["Address_Street","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->Address_Street->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Address_Street'] = &$this->Address_Street;

        // Address_Zipcode
        $this->Address_Zipcode = new DbField(
            $this, // Table
            'x_Address_Zipcode', // Variable name
            'Address_Zipcode', // Name
            '`Address_Zipcode`', // Expression
            '`Address_Zipcode`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Address_Zipcode`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Address_Zipcode->InputTextType = "text";
        $this->Address_Zipcode->Required = true; // Required field
        $this->Address_Zipcode->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->Address_Zipcode->Lookup = new Lookup('Address_Zipcode', 'user', true, 'Address_Zipcode', ["Address_Zipcode","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->Address_Zipcode->Lookup = new Lookup('Address_Zipcode', 'user', true, 'Address_Zipcode', ["Address_Zipcode","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->Address_Zipcode->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Address_Zipcode'] = &$this->Address_Zipcode;

        // Address_City
        $this->Address_City = new DbField(
            $this, // Table
            'x_Address_City', // Variable name
            'Address_City', // Name
            '`Address_City`', // Expression
            '`Address_City`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Address_City`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Address_City->InputTextType = "text";
        $this->Address_City->Required = true; // Required field
        $this->Address_City->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->Address_City->Lookup = new Lookup('Address_City', 'user', true, 'Address_City', ["Address_City","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->Address_City->Lookup = new Lookup('Address_City', 'user', true, 'Address_City', ["Address_City","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->Address_City->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Address_City'] = &$this->Address_City;

        // Address_Country
        $this->Address_Country = new DbField(
            $this, // Table
            'x_Address_Country', // Variable name
            'Address_Country', // Name
            '`Address_Country`', // Expression
            '`Address_Country`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Address_Country`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Address_Country->InputTextType = "text";
        $this->Address_Country->Required = true; // Required field
        $this->Address_Country->UseFilter = true; // Table header filter
        switch ($CurrentLanguage) {
            case "en-US":
                $this->Address_Country->Lookup = new Lookup('Address_Country', 'user', true, 'Address_Country', ["Address_Country","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->Address_Country->Lookup = new Lookup('Address_Country', 'user', true, 'Address_Country', ["Address_Country","","",""], '', '', [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->Address_Country->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['Address_Country'] = &$this->Address_Country;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`user`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $success = $this->insertSql($rs)->execute();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($success) {
            // Get insert id if necessary
            $this->id->setDbValue($conn->lastInsertId());
            $rs['id'] = $this->id->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->execute();
            $success = ($success > 0) ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['id']) && !EmptyValue($this->id->CurrentValue)) {
                $rs['id'] = $this->id->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('id', $rs)) {
                AddFilter($where, QuotedName('id', $this->Dbid) . '=' . QuotedValue($rs['id'], $this->id->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->execute();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->id->DbValue = $row['id'];
        $this->User_Email->DbValue = $row['User_Email'];
        $this->User_Name->DbValue = $row['User_Name'];
        $this->User_Employee_Number->DbValue = $row['User_Employee_Number'];
        $this->User_Phone_Number->DbValue = $row['User_Phone_Number'];
        $this->Address_Name->DbValue = $row['Address_Name'];
        $this->Address_Street->DbValue = $row['Address_Street'];
        $this->Address_Zipcode->DbValue = $row['Address_Zipcode'];
        $this->Address_City->DbValue = $row['Address_City'];
        $this->Address_Country->DbValue = $row['Address_Country'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`id` = @id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->id->CurrentValue : $this->id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->id->CurrentValue = $keys[0];
            } else {
                $this->id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('id', $row) ? $row['id'] : null;
        } else {
            $val = !EmptyValue($this->id->OldValue) && !$current ? $this->id->OldValue : $this->id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("userlist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "userview") {
            return $Language->phrase("View");
        } elseif ($pageName == "useredit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "useradd") {
            return $Language->phrase("Add");
        }
        return "";
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "UserView";
            case Config("API_ADD_ACTION"):
                return "UserAdd";
            case Config("API_EDIT_ACTION"):
                return "UserEdit";
            case Config("API_DELETE_ACTION"):
                return "UserDelete";
            case Config("API_LIST_ACTION"):
                return "UserList";
            default:
                return "";
        }
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "userlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("userview", $parm);
        } else {
            $url = $this->keyUrl("userview", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "useradd?" . $parm;
        } else {
            $url = "useradd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("useredit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("userlist", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("useradd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("userlist", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("userdelete");
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"id\":" . JsonEncode($this->id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language, $Page;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;dashboard=true";
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("id") ?? Route("id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->id->CurrentValue = $key;
            } else {
                $this->id->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->id->setDbValue($row['id']);
        $this->User_Email->setDbValue($row['User_Email']);
        $this->User_Name->setDbValue($row['User_Name']);
        $this->User_Employee_Number->setDbValue($row['User_Employee_Number']);
        $this->User_Phone_Number->setDbValue($row['User_Phone_Number']);
        $this->Address_Name->setDbValue($row['Address_Name']);
        $this->Address_Street->setDbValue($row['Address_Street']);
        $this->Address_Zipcode->setDbValue($row['Address_Zipcode']);
        $this->Address_City->setDbValue($row['Address_City']);
        $this->Address_Country->setDbValue($row['Address_Country']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "UserList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id
        $this->id->CellCssStyle = "white-space: nowrap;";

        // User_Email

        // User_Name

        // User_Employee_Number

        // User_Phone_Number

        // Address_Name

        // Address_Street

        // Address_Zipcode

        // Address_City

        // Address_Country

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

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

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

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

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // id
        $this->id->setupEditAttributes();
        $this->id->EditValue = $this->id->CurrentValue;

        // User_Email
        $this->User_Email->setupEditAttributes();
        if (!$this->User_Email->Raw) {
            $this->User_Email->CurrentValue = HtmlDecode($this->User_Email->CurrentValue);
        }
        $this->User_Email->EditValue = $this->User_Email->CurrentValue;
        $this->User_Email->PlaceHolder = RemoveHtml($this->User_Email->caption());

        // User_Name
        $this->User_Name->setupEditAttributes();
        if (!$this->User_Name->Raw) {
            $this->User_Name->CurrentValue = HtmlDecode($this->User_Name->CurrentValue);
        }
        $this->User_Name->EditValue = $this->User_Name->CurrentValue;
        $this->User_Name->PlaceHolder = RemoveHtml($this->User_Name->caption());

        // User_Employee_Number
        $this->User_Employee_Number->setupEditAttributes();
        if (!$this->User_Employee_Number->Raw) {
            $this->User_Employee_Number->CurrentValue = HtmlDecode($this->User_Employee_Number->CurrentValue);
        }
        $this->User_Employee_Number->EditValue = $this->User_Employee_Number->CurrentValue;
        $this->User_Employee_Number->PlaceHolder = RemoveHtml($this->User_Employee_Number->caption());

        // User_Phone_Number
        $this->User_Phone_Number->setupEditAttributes();
        if (!$this->User_Phone_Number->Raw) {
            $this->User_Phone_Number->CurrentValue = HtmlDecode($this->User_Phone_Number->CurrentValue);
        }
        $this->User_Phone_Number->EditValue = $this->User_Phone_Number->CurrentValue;
        $this->User_Phone_Number->PlaceHolder = RemoveHtml($this->User_Phone_Number->caption());

        // Address_Name
        $this->Address_Name->setupEditAttributes();
        if (!$this->Address_Name->Raw) {
            $this->Address_Name->CurrentValue = HtmlDecode($this->Address_Name->CurrentValue);
        }
        $this->Address_Name->EditValue = $this->Address_Name->CurrentValue;
        $this->Address_Name->PlaceHolder = RemoveHtml($this->Address_Name->caption());

        // Address_Street
        $this->Address_Street->setupEditAttributes();
        if (!$this->Address_Street->Raw) {
            $this->Address_Street->CurrentValue = HtmlDecode($this->Address_Street->CurrentValue);
        }
        $this->Address_Street->EditValue = $this->Address_Street->CurrentValue;
        $this->Address_Street->PlaceHolder = RemoveHtml($this->Address_Street->caption());

        // Address_Zipcode
        $this->Address_Zipcode->setupEditAttributes();
        if (!$this->Address_Zipcode->Raw) {
            $this->Address_Zipcode->CurrentValue = HtmlDecode($this->Address_Zipcode->CurrentValue);
        }
        $this->Address_Zipcode->EditValue = $this->Address_Zipcode->CurrentValue;
        $this->Address_Zipcode->PlaceHolder = RemoveHtml($this->Address_Zipcode->caption());

        // Address_City
        $this->Address_City->setupEditAttributes();
        if (!$this->Address_City->Raw) {
            $this->Address_City->CurrentValue = HtmlDecode($this->Address_City->CurrentValue);
        }
        $this->Address_City->EditValue = $this->Address_City->CurrentValue;
        $this->Address_City->PlaceHolder = RemoveHtml($this->Address_City->caption());

        // Address_Country
        $this->Address_Country->setupEditAttributes();
        if (!$this->Address_Country->Raw) {
            $this->Address_Country->CurrentValue = HtmlDecode($this->Address_Country->CurrentValue);
        }
        $this->Address_Country->EditValue = $this->Address_Country->CurrentValue;
        $this->Address_Country->PlaceHolder = RemoveHtml($this->Address_Country->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->User_Email);
                    $doc->exportCaption($this->User_Name);
                    $doc->exportCaption($this->User_Employee_Number);
                    $doc->exportCaption($this->User_Phone_Number);
                    $doc->exportCaption($this->Address_Name);
                    $doc->exportCaption($this->Address_Street);
                    $doc->exportCaption($this->Address_Zipcode);
                    $doc->exportCaption($this->Address_City);
                    $doc->exportCaption($this->Address_Country);
                } else {
                    $doc->exportCaption($this->User_Email);
                    $doc->exportCaption($this->User_Name);
                    $doc->exportCaption($this->User_Employee_Number);
                    $doc->exportCaption($this->User_Phone_Number);
                    $doc->exportCaption($this->Address_Name);
                    $doc->exportCaption($this->Address_Street);
                    $doc->exportCaption($this->Address_Zipcode);
                    $doc->exportCaption($this->Address_City);
                    $doc->exportCaption($this->Address_Country);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->User_Email);
                        $doc->exportField($this->User_Name);
                        $doc->exportField($this->User_Employee_Number);
                        $doc->exportField($this->User_Phone_Number);
                        $doc->exportField($this->Address_Name);
                        $doc->exportField($this->Address_Street);
                        $doc->exportField($this->Address_Zipcode);
                        $doc->exportField($this->Address_City);
                        $doc->exportField($this->Address_Country);
                    } else {
                        $doc->exportField($this->User_Email);
                        $doc->exportField($this->User_Name);
                        $doc->exportField($this->User_Employee_Number);
                        $doc->exportField($this->User_Phone_Number);
                        $doc->exportField($this->Address_Name);
                        $doc->exportField($this->Address_Street);
                        $doc->exportField($this->Address_Zipcode);
                        $doc->exportField($this->Address_City);
                        $doc->exportField($this->Address_Country);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
