

function GetQueryStringParams(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

function AppendErrorToTable(DataObj, TableToAdd) {
    var ErrorMsgs = DataObj;   
    $('<tr/>', {
        'text': ErrorMsgs
    }).appendTo(TableToAdd);
    console.log(TableToAdd);
}

function StartRetrievalMethod(PerformRequest, RetrievalMethodName, TableToAdd, RetrievalMethodClass, RetrievalMethodData ) {

    if (PerformRequest == "yes") {
        try {
            for (var i = 0; i < RetrievalMethodData["Items"].length; i++) {
                $('<tr/>', {
                    'id': RetrievalMethodName + 'Tr' + i,
                }).appendTo(TableToAdd);
                $('<td/>', {
                    'text': RetrievalMethodData["Items"][i].Value
                }).appendTo('#' + RetrievalMethodName + 'Tr' + i);
                console.log(RetrievalMethodData["Items"][i].Value);
                $('<td/>', {
                    'text': RetrievalMethodData["Items"][i].Timestamp
                }).appendTo('#' + RetrievalMethodName + 'Tr' + i);
            }
        }
        catch (e) {
            if (RetrievalMethodData["Value"] != undefined) {
                $('<tr/>', {
                    'id': RetrievalMethodName + 'Tr',
                }).appendTo(TableToAdd);
                $('<td/>', {
                    'text': RetrievalMethodData["Value"]
                }).appendTo('#' + RetrievalMethodName + 'Tr');
                console.log(RetrievalMethodData["Items"][i].Value);
                $('<td/>', {
                    'text': RetrievalMethodData["Timestamp"]
                }).appendTo('#' + RetrievalMethodName + 'Tr');
            }
            else {
                AppendErrorToTable(RetrievalMethodData, TableToAdd);
            }
        }

        $(RetrievalMethodClass).css("visibility", "visible")
    }
    else {
        $(RetrievalMethodClass).css("visibility", "hidden")
    }


}

var piServerName = "";
var piPointName = "";
var startTime = "";
var endTime = "";
var interval = "";

$(document).ready(function () {
    alert("here");
    console.log("here");
    piServerName = GetQueryStringParams('piServerName');
    piPointName = GetQueryStringParams('piPointName');
    startTime = GetQueryStringParams('startTime');
    endTime = GetQueryStringParams('endTime');
    interval = GetQueryStringParams('interval');
    var getsnap = GetQueryStringParams('getsnap');
    var getrec = GetQueryStringParams('getrec');
    var getint = GetQueryStringParams('getint');
    $("#PIServerNameValue").text(piServerName);
    $("#PIPointNameValue").text(piPointName);
    $("#StartTimeValue").text(startTime);
    $("#EndTimeValue").text(endTime);
    $("#IntervalValue").text(interval);
alert("here");
    piwebapi.SetBaseServiceUrl("https://localhost:4430/piwebapi/");
    //piwebapi.SetBaseServiceUrl("https://demo.piwebapi.net/piwebapi/");
    piwebapi.ValidPIServerName(piServerName, (function (PIServerExist) { $("#PIServerExistValue").text(PIServerExist); }));
    piwebapi.ValidPIPointName(piServerName, piPointName, (function (PIPointExist) { $("#PIPointExistValue").text(PIPointExist); }));
    piwebapi.GetSnapshotValue(piServerName, piPointName, (function (data) { StartRetrievalMethod(getsnap, 'Snapshot', 'table.snapshot', ".snapshot", data) }));
    piwebapi.GetRecordedValues(piServerName, piPointName, startTime, endTime, (function (data) { StartRetrievalMethod(getrec, 'Recorded', 'table.recorded', ".recorded", data) }));
    piwebapi.GetInterpolatedValues(piServerName, piPointName, startTime, endTime, interval, (function (data) { StartRetrievalMethod(getint, 'Interpolated', 'table.interpolated', ".interpolated", data) }));
});
