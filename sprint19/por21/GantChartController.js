
// Total Dataset Response Data
var gridTotalResponseData;
var currentFilteredData;

//-------------------------------------------------FILTER Variables-------------------------------------------------------------//
//Filter set for all filter Columns, grabs all unique records within each set
var excludedFilterValues = ["Year", "Region", "Market", "Facility", "Program", "Project", "CR", "Select All"];
var filterDataSet = {
    YearSet: [],
    RegionSet: [],
    MarketSet: [],
    FacilitySet: [],
    ProgramSet: [],
    ProjectSet: [],
    CRSet: []
};

var objectModal = {
    Primary_Key: 'string',
    Region: 'string',
    Market: 'string',
    Facility: 'string',
    Program: 'string',
    Project: 'string',
    Equipment_ID: 'string',
    Shipping_DT: 'string',
    Activation_Month: 'string',
    Migration_Month: 'string',
    CR_ID: 'string',
    EPA: 'string',
    From_Shipping_DT: 'string',
    Activation_DT: 'string',
    Migration_DT: 'string'
};

/**
 *
 * Main function, called on page load, Fetches Chart data and creates Filter and Table.
 */
function createGantChart() {
    this.getChartData().then(gantChartData => {
        currentFilteredData = gantChartData;
        gridTotalResponseData = gantChartData;
        this.createFilterForTable(gantChartData);
        this.addFormattedDataToTable(gantChartData);
    });
}

/**
 *
 * For this case, it is calling the php file and getting the response back.
 * @returns {Promise<>}
 */
getChartData = async function () {
    const response = await fetch('GantChartConnection.php');
    return await response.json();
};

/**
 *
 * Puts formatted Data in a table, using the response received
 * @param response
 */
function addFormattedDataToTable(response) {
    let formattedTrData = '';
    response.forEach(function(record) {
        if (record.Primary_Key) {
            let newData = '<tr>' +
                '<td>' + record.Region + '</td>' +
                '<td>' + record.Market + '</td>' +
                '<td>' + record.Facility + '</td>' +
                '<td>' + record.Program + '</td>' +
				'<td>' + record.Program_Group + '</td>' +
				'<td>' + record.Sub_Program + '</td>' +
                '<td>' + record.Project + '</td>' +
                '<td>' + record.Equipment_ID + '</td>' +
                '<td>' + record.KitType_Name + '</td>' +
                '<td>' + record.Kit_Name + '</td>' +
                '<td>' + record.toQuantity + '</td>' +
                '<td>' + record.Shipping_DT + '</td>' +
                '<td>' + record.Activation_DT + '</td>' +
                '<td>' + record.Migration_DT + '</td>' +
                '<td>' + record.CR_ID + '</td>' +
                '<td>' + record.EPA + '</td>' +
                getCharHighlightMonth(record) +
            '</tr>';
            formattedTrData += newData;
        }
    }, formattedTrData);
    document.getElementById("gridTableContainer").innerHTML = "";
    document.getElementById("gridTableContainer").innerHTML = formattedTrData;
    this.removeMask();
}

/**
 * All static horizontal Months for Gant Chart
 * @param record
 * @returns {string}
 */
function getCharHighlightMonth(record) {
    const gantWeekRange = getWeeklyDate('10/01/2020', '03/31/2022');
    let finalTd = '';
    gantWeekRange.forEach(eachWeek => {
        finalTd += '<td class=' + getCorrectTdCss(eachWeek, record) + '</td>'
    });
    return finalTd;
}

/**
 * Main CSS Function that calls the Dot and the colors
 * @param tableCellDate
 * @param record
 * @returns {string}
 */
function getCorrectTdCss(tableCellDate, record) {
    const toCss = this.getToShipmentActivationMigrationCSS(tableCellDate, record);
    const fromDot = this.getCorrectDot(tableCellDate, record);
    return toCss + '>' + fromDot;
}

/**
 *
 * CSS 1: Function to compare Celldate vs Start Date, End Date, Migration Date and return color for Gantt.
 * @param tableCellDate
 * @param record
 * @returns {string}
 */
function getToShipmentActivationMigrationCSS(tableCellDate, record) {
    const cellDate = new Date(tableCellDate + 'GMT-0500 (Eastern Standard Time)');
    const startDate = (record.Shipping_DT) ? new Date(record.Shipping_DT + 'GMT-0500 (Eastern Standard Time)') : null;
    const endDate = (record.Activation_DT) ? new Date(record.Activation_DT + 'GMT-0500 (Eastern Standard Time)') : null;
    const migrationDate = (record.Migration_DT) ? new Date(record.Migration_DT + 'GMT-0500 (Eastern Standard Time)') : null;
    let cellColor = "not-range-date";

    // Date between Need By dt and Activation data, if cell date > start and CellDate < end for Grey color
    if (cellDate && endDate && startDate) {
        if((cellDate >= startDate) && (cellDate <= endDate)) {
            cellColor = "range-date";
        }
    }

    // Start Date: Assuming it is prior to Activation and migration month
    if (cellDate && startDate) {
        if (cellDate.getYear() === startDate.getYear() && this.getWeekNumber(cellDate) === this.getWeekNumber(startDate)) {
            cellColor = "start-date";
        }
    }

    // End Month Fpr
    if (cellDate && endDate) {
        if (cellDate.getYear() === endDate.getYear() && cellDate.getMonth() === endDate.getMonth()) {
            cellColor = "end-date";
        }
    }
    // Migration Month
    if (cellDate && migrationDate) {
        if (cellDate.getYear() === migrationDate.getYear() && cellDate.getMonth() === migrationDate.getMonth()) {
            cellColor = "migration-date";
        }
    }
    return cellColor;
}

/**
 * CSS 2: Function to compare Cell date vs FROM Start Date, FROM End Date, and  returns dot for the GANT
 * @param tableCellDate
 * @param record
 * @returns {string}
 */
function getCorrectDot(tableCellDate, record) {
    //For From Shipping and Activation CSS Dots
    const cellDate = new Date(tableCellDate + 'GMT-0500 (Eastern Standard Time)');
    const startFrom = (record.From_Shipping_DT) ? new Date(record.From_Shipping_DT + 'GMT-0500 (Eastern Standard Time)') : null;
    const endFrom = (record.From_Activation_DT) ? new Date(record.From_Activation_DT + 'GMT-0500 (Eastern Standard Time)') : null;
    let dot = "";

    // If CR is not Blank (if CR exists) then populate shipping and activation otherwise populate "blank"
    if (record.EPA !== "" && (record.Activation_DT !== record.From_Activation_DT) || (record.Shipping_DT !== record.From_Shipping_DT) ) {

        // Range from Start From End
        if (cellDate && endFrom && startFrom) {
            if((cellDate >= startFrom) && (cellDate <= endFrom)) {
                dot = "<div align='center'>&#8226;</div>";
            }
        }

        // fromstart : Year and week
        if (cellDate && startFrom) {
            if (cellDate.getYear() === startFrom.getYear() && this.getWeekNumber(cellDate) === this.getWeekNumber(startFrom)) {
                dot = "<div align='center'>&#8226;</div>";
            }
        }

        // fromend year and month
        if (cellDate && endFrom) {
            if (cellDate.getYear() === endFrom.getYear() && cellDate.getMonth() === endFrom.getMonth()) {
                dot = "<div align='center'>&#8226;</div>";
            }
        }
    }
    else {
        dot = "";
    }
    return dot;
}

/**
 *
 * Takes response and Creates Unique record per column Datasets(Region, Market, Facility, Program and Project)
 * @param response
 */
function createFilterForTable(response) {
    //for each record from response, do below.
    response.forEach(function(record) {
            (!filterDataSet.YearSet.includes(record.Fiscal_Year)) ? filterDataSet.YearSet.push((record.Fiscal_Year)): null;
            (!filterDataSet.RegionSet.includes(record.Region)) ? filterDataSet.RegionSet.push((record.Region)): null;
            (!filterDataSet.MarketSet.includes(record.Market)) ? filterDataSet.MarketSet.push((record.Market)): null;
            (!filterDataSet.FacilitySet.includes(record.Facility)) ? filterDataSet.FacilitySet.push((record.Facility)): null;
            (!filterDataSet.ProgramSet.includes(record.Program)) ? filterDataSet.ProgramSet.push((record.Program)): null;
            (!filterDataSet.ProjectSet.includes(record.Project)) ? filterDataSet.ProjectSet.push((record.Project)): null;
            (!filterDataSet.CRSet.includes(record.CRR)) ? filterDataSet.CRSet.push((record.CRR)): null;
    });
    this.createCombobox();
}
/**
 *
 *
 * Onces the Set's are created above,  we create a combo box/dropdown, which holds keys and passed into HTML
 * 1) Gridfiltercontainer holds an Id to pass on our HTML Div ID
 * 2) Eachcombobox html stores the key, the substring of it
 * 3) we take the filter key, holds all keys, and pass it with each comboboxhtml
 * 4)eachcomboboxhtml then is looped and stored into totalhtml
 */
function createCombobox() {
    let gridFilterContainer = document.getElementById('grid-filter-container');
    let totalHtml = '';
    for (let key in filterDataSet) {
        //Combobox dropdown elements
        let eachComboBoxHtml =
            '<select class="combobox-cls" onchange=onComboBoxItemSelect(event) id=' + key + '>' +
            '<option selected disabled>' + key.substring(0, key.length - 3) + '</option>' +
            '<option>Select All</option>';
        let filterValues = filterDataSet[key];
        //Sorts in ascending order FilterValues
        filterValues.sort();
        //Remaining list of values from key
        filterValues.forEach(function(record) {
            eachComboBoxHtml += '<option>' + record + '</option>'
        }, eachComboBoxHtml);

        eachComboBoxHtml += '</select>' + ' ';
        totalHtml += eachComboBoxHtml;
    }
    gridFilterContainer.innerHTML = totalHtml;
}
/**
 *
 */
function onComboBoxItemSelect() {
    this.addMask();
    const selectedFilters = this.getFilterSelection();
    const filteredResponse = this.getFilteredData(selectedFilters);
    this.addFormattedDataToTable(filteredResponse);
}

/**
 *
 * @returns {{YearSet: Array, CRSet: Array, FacilitySet: Array, ProgramSet: Array, RegionSet: Array, MarketSet: Array, ProjectSet: Array}}
 */
function getFilterSelection() {
    let appliedFilter = {
        YearSet: [],
        RegionSet: [],
        MarketSet: [],
        FacilitySet: [],
        ProgramSet: [],
        ProjectSet: [],
        CRSet: []
    };

    for (const key in filterDataSet) {
        const comboboxElement = document.getElementById(key);
        const comboboxValue = comboboxElement.value;
        if(!excludedFilterValues.includes(comboboxValue)) {
            appliedFilter[key].push(comboboxValue);
        }
    }
    return appliedFilter;
}

/**
 *
 * forMATCH: If the length of the set is not 0 then its true else its false, AFTERMATCH:
 * if matched then populate record(true)
 * @param selectedFilters
 * @returns {*}
 */
function getFilteredData(selectedFilters) {
    return gridTotalResponseData.filter(function (record) {
        const matchYear = (selectedFilters.YearSet.length !== 0);
        const matchRegions = (selectedFilters.RegionSet.length !== 0);
        const matchMarket = (selectedFilters.MarketSet.length !== 0);
        const matchFacilities = (selectedFilters.FacilitySet.length !== 0);
        const matchProgram = (selectedFilters.ProgramSet.length !== 0);
        const matchProject = (selectedFilters.ProjectSet.length !== 0);
        const matchcr = (selectedFilters.CRSet.length !== 0);

        const yearMatched = (matchYear) ? ((selectedFilters.YearSet.includes(record.Fiscal_Year.toString()))) : true;
        const regionMatched = (matchRegions) ? ((selectedFilters.RegionSet.includes(record.Region))) : true;
        const marketMatched = (matchMarket) ? ((selectedFilters.MarketSet.includes(record.Market))) : true;
        const facilityMatched = (matchFacilities) ? ((selectedFilters.FacilitySet.includes(record.Facility))) : true;
        const programMatched = (matchProgram) ? ((selectedFilters.ProgramSet.includes(record.Program))) : true;
        const projectMatched = (matchProject) ? ((selectedFilters.ProjectSet.includes(record.Project))) : true;
        const crMatched = (matchcr) ? ((selectedFilters.CRSet.includes(record.CRR))) : true;

        return yearMatched && regionMatched && marketMatched && facilityMatched && programMatched && projectMatched && crMatched;
    });
}

/**
 * Clear All Filter Dataset
 */
function onResetFilterClick() {
    this.addMask();
    for (const key in filterDataSet) {
        document.getElementById(key).value = key.substring(0, key.length - 3);
    }
    this.addFormattedDataToTable(gridTotalResponseData);
}

/**
 * Gets weekly Date Range
 * @param startDate
 * @param endDate
 * @returns {Array}
 */
function getWeeklyDate(startDate, endDate) {
    let weeklyDateArray = [];
    startDate = new Date(startDate);
    endDate = new Date(endDate);
    while (startDate <= endDate) {
        weeklyDateArray.push(startDate.toLocaleDateString());
        startDate = new Date(startDate.setDate(startDate.getDate() + 7));
    }
    return weeklyDateArray;
}

/**
 * -------------------Finds week of the month (StackOverflow)-------------------------------------------------------//
 * https://stackoverflow.com/questions/6117814/get-week-of-year-in-javascript-like-in-php
 // Copy date so don't modify original
 // Set to nearest Thursday: current date + 4 - current day number
 // Make Sunday's day number 7
 * @param dateToCheck
 * @returns {number}
 */
function getWeekNumber(dateToCheck) {
    dateToCheck = new Date(Date.UTC(dateToCheck.getFullYear(), dateToCheck.getMonth(), dateToCheck.getDate()));
    dateToCheck.setUTCDate(dateToCheck.getUTCDate() + 4 - (dateToCheck.getUTCDay() || 7));
    // Get first day of year
    const yearStart = new Date(Date.UTC(dateToCheck.getUTCFullYear(), 0, 1));
    // Calculate full weeks to nearest Thursday
    return Math.ceil((((dateToCheck - yearStart) / 86400000) + 1) / 7);
}

/**
 * Adding the mask
 */
function addMask() {
        document.getElementById('gridTableContainer').classList.add('display-none');
        document.getElementById('loader').classList.remove('display-none');
}

/**
 * removing the mask
 */
function removeMask() {
        document.getElementById('gridTableContainer').classList.remove('display-none');
        document.getElementById('loader').classList.add('display-none');
}

/**
 *
 * -----------------------------------------Excel Import(StackOverflow)--------------
 * https://stackoverflow.com/questions/22317951/export-html-table-data-to-excel-using-javascript-jquery-is-not-working-properl
 * @returns {*}
 */
// function fnExcelReport() {
//     let tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
//     var textRange; var j=0;
//     tab = document.getElementById('tablestyle'); // id of table

//     for(j = 0 ; j < tab.rows.length ; j++)
//     {
//         tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
//         //tab_text=tab_text+"</tr>";
//     }

//     tab_text=tab_text+"</table>";
//     tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
//     tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
//     tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // removes input params

//     var ua = window.navigator.userAgent;
//     var msie = ua.indexOf("MSIE ");

//     if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
//     {
//         txtArea1.document.open("txt/html","replace");
//         txtArea1.document.write(tab_text);
//         txtArea1.document.close();
//         txtArea1.focus();
//         sa=txtArea1.document.execCommand("SaveAs",true,"Thank You.xls");
//     }
//     else                 //other browser not tested on IE 11
//         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

//     return (sa);
// }


function fnExcelReport() {
    let a;
    let tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    let tab = document.getElementById('tablestyle');
    if (tab==null) {
        return false;
    }
    if (tab.rows.length === 0) {
        return false;
    }

    for (let j = 0 ; j < tab.rows.length ; j++) {
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        try {
            let blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
            window.URL = window.URL || window.webkitURL;
            let link = window.URL.createObjectURL(blob);
            a = document.createElement("a");
            if (document.getElementById("caption")!=null) {
                a.download=document.getElementById("caption").innerText;
            }
            else {
                a.download = 'por20-data.xls';
            }

            a.href = link;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        } catch (e) {
        }
    return false;
}



