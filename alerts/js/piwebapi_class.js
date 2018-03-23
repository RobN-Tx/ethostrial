var piwebapi = (function () {
    var base_service_url = "NotDefined";

    function GetJsonContent(url, SuccessCallBack, ErrorCallBack) {
        console.log(url);
        console.log("GetJson");
        $.ajax({
            type: 'GET',
            accept: '*/*',
            url: url,
            cache: false,
            async: true,
            success: SuccessCallBack,
            error: ErrorCallBack

        });
    }

    function CheckPIServerName(piServerName, UpdateDOM) {
        BaseUrlCheck();
        var url = base_service_url + "dataservers?name=" + piServerName;
        console.log("CheckPIServerName");
        console.log(url);
        GetJsonContent(url, (function (piServerJsonData) {
            UpdateDOM(true);
        }), (function () {
            UpdateDOM(false);
        }));
    }


    function CheckPIPointName(piServerName, piPointName, UpdateDOM) {

        BaseUrlCheck();
        url = base_service_url + "points?path=\\\\" + piServerName + "\\" + piPointName;
        GetJsonContent(url, (function (piPointJsonData) {
            piPointLinksJsonData = piPointJsonData;
            UpdateDOM(true);
        }), (function () {
            UpdateDOM(false)
        }));
    }



    function GetData(piServerName, piPointName, ServiceUrl, QueryString, UpdateDOM) {
        BaseUrlCheck();
        url = base_service_url + "points?path=\\\\" + piServerName + "\\" + piPointName;
        GetJsonContent(url, (function (piPointJsonData) {
            var url_data = piPointJsonData["Links"][ServiceUrl] + QueryString;
            GetJsonContent(url_data, (function (JsonData) {
                UpdateDOM(JsonData);
            }), (function () {
                UpdateDOM("Error: Parameters are incorrect.");
            }));
        }), (function () {
            UpdateDOM("Error: Could not find PI Point on the selected PI Data Archive.");
        }));
    }

    function BaseUrlCheck() {
        if (base_service_url == "NotDefined") {
            alert("Service base url was not defined");
        }
    }

    return {
        ValidPIServerName: function (piServerName, UpdateDOM) {
            CheckPIServerName(piServerName, UpdateDOM)
        },

        ValidPIPointName: function (piServerName, piPointName, UpdateDOM) {
            CheckPIPointName(piServerName, piPointName, UpdateDOM);
        },

        GetSnapshotValue: function (piServerName, piPointName, UpdateDOM) {
            GetData(piServerName, piPointName, "Value", "", UpdateDOM);
        },
        GetRecordedValues: function (piServerName, piPointName, startTime, endTime, UpdateDOM) {
            GetData(piServerName, piPointName, "RecordedData", "?starttime=" + startTime + "&endtime=" + endTime, UpdateDOM);
        },
        GetInterpolatedValues: function (piServerName, piPointName, startTime, endTime, interval, UpdateDOM) {
            GetData(piServerName, piPointName, "InterpolatedData", "?starttime=" + startTime + "&endtime=" + endTime + "&interval=" + interval, UpdateDOM);
        },
        SetBaseServiceUrl: function (baseUrl) {
            base_service_url = baseUrl;
            if (base_service_url.slice(-1) != "/") {
                base_service_url = base_service_url + "/";
            }
        }
    }
}());
