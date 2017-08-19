
/**
 * An autosuggest textbox control.
 * @class
 * @scope public
 */
function AutoSuggestControl(oTextbox /*:HTMLInputElement*/, 
                            oProvider /*:SuggestionProvider*/,
                            oTask /*:Task*/) {
    /**
     * The currently selected suggestions.
     * @scope private
     */   
    this.cur /*:int*/ = -1;

    /**
     * The dropdown list layer.
     * @scope private
     */
    this.layer = null;
    
    /**
     * Suggestion provider for the autosuggest feature.
     * @scope private.
     */
    this.provider /*:SuggestionProvider*/ = oProvider;
    
    /**
     * The textbox to capture.
     * @scope private
     */
    this.textbox /*:HTMLInputElement*/ = oTextbox;
    
    /**
     * Timeout ID for fast typers.
     * @scope private
     */
    this.timeoutId /*:int*/ = null;

    this.task = oTask;

    /**
     * The text that the user typed.
     * @scope private
     */
    this.userText /*:String*/ = oTextbox.value;
    
    //initialize the control
    this.init(this.task);
    
}

/**
 * Autosuggests one or more suggestions for what the user has typed.
 * If no suggestions are passed in, then no autosuggest occurs.
 * @scope private
 * @param aSuggestions An array of suggestion strings.
 * @param bTypeAhead If the control should provide a type ahead suggestion.
 */
AutoSuggestControl.prototype.autosuggest = function (aSuggestions /*:Array*/,
                                                     bTypeAhead /*:boolean*/,
                                                     cTask) {
    
    //re-initialize pointer to current suggestion
    this.cur = -1;
    
    //make sure there's at least one suggestion
    if (aSuggestions.length > 0) {
        if (bTypeAhead) {
            if (cTask == 'ledgerGroups') {
                this.typeAhead(aSuggestions[0]['groupName']);
            }
            else if (cTask == 'companyGroups') {
                this.typeAhead(aSuggestions[0]['groupName']);
            }
            else if (cTask == 'rollNumber' || cTask == 'fineRollNumber') {
                this.typeAhead(aSuggestions[0]['rollNo']);
            }
            else if (cTask == 'receiptVoucherDr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'receiptVoucherCr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'paymentVoucherDr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'paymentVoucherCr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'journalVoucherDr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'journalVoucherCr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'contraVoucherDr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            else if (cTask == 'contraVoucherCr') {
                this.typeAhead(aSuggestions[0]['ledgerName']);
            }
            
        }
        
        this.showSuggestions(aSuggestions, cTask);
    } else {
        this.hideSuggestions();
    }
};

/**
 * Creates the dropdown layer to display multiple suggestions.
 * @scope private
 */
AutoSuggestControl.prototype.createDropDown = function (task) {


    
    //create the layer and assign styles

    this.layer = document.createElement("div");
    this.layer.className = "suggestions";
    this.layer.style.visibility = "hidden";
    this.layer.style.width = this.textbox.offsetWidth;
    
    this.layer.style.height = '50px';

    if (task == 'ledgerGroups') {
        this.layer.style.width = '142px';
    }
    else if (task == 'companyGroups') {
        this.layer.style.width = '142px';
        this.layer.style.height = '40px';
    }
    else if (task == 'rollNumber' || task == 'fineRollNumber') {
        this.layer.style.width = '142px';
        this.layer.style.height = '100px';
    }
    else if (task == 'receiptVoucherDr' || task == 'receiptVoucherCr'  || task == 'paymentVoucherDr' || task == 'paymentVoucherCr' || task == 'journalVoucherDr' || task == 'journalVoucherCr' || task == 'contraVoucherDr' || task == 'contraVoucherCr') {
        this.layer.style.width = '735px';
        this.layer.style.height = '200px';
    }

    
    this.layer.style.overflow = 'auto';
    this.layer.style.position = 'fixed';
    var browserName=navigator.appName;
    if (browserName=="Netscape") {
        this.layer.style.position = 'fixed';
    }
    else if (browserName=="Microsoft Internet Explorer") {
            this.layer.style.position = 'absolute';
    }

    
    this.layer.style.zIndex = 2000;

    document.body.appendChild(this.layer);    
    
    //when the user clicks on the a suggestion, get the text (innerHTML)
    //and place it into a textbox
    var oThis = this;
    this.layer.onmousedown = 
    this.layer.onmouseup = 
    this.layer.onmouseover = function (oEvent) {
        oEvent = oEvent || window.event;
        oTarget = oEvent.target || oEvent.srcElement;

        if (oEvent.type == "mousedown") {
			if (oTarget.firstChild.nodeValue != 'null' && oTarget.firstChild.nodeValue != null) {
				oThis.textbox.value = oTarget.firstChild.nodeValue;
			}
            oThis.hideSuggestions();
        } else if (oEvent.type == "mouseover") {
            oThis.highlightSuggestion(oTarget);
        } else {
            oThis.textbox.focus();
        }
    };
    
};

/**
 * Gets the left coordinate of the textbox.
 * @scope private
 * @return The left coordinate of the textbox in pixels.
 */
AutoSuggestControl.prototype.getLeft = function () /*:int*/ {

    var oNode = this.textbox;
    var iLeft = 0;
    
    try {
        while(oNode.tagName != "BODY") {
            iLeft += oNode.offsetLeft;
            oNode = oNode.offsetParent;        
        }
    }
    catch(e) {}
    
    return iLeft;
};

/**
 * Gets the top coordinate of the textbox.
 * @scope private
 * @return The top coordinate of the textbox in pixels.
 */
AutoSuggestControl.prototype.getTop = function () /*:int*/ {

    var oNode = this.textbox;
    var iTop = 0;
    
    try{
        while(oNode.tagName != "BODY") {
            iTop += oNode.offsetTop;
            oNode = oNode.offsetParent;
        }
    }
    catch(e){}
    
    return iTop;
};

/**
 * Highlights the next or previous suggestion in the dropdown and
 * places the suggestion into the textbox.
 * @param iDiff Either a positive or negative number indicating whether
 *              to select the next or previous sugggestion, respectively.
 * @scope private
 */
AutoSuggestControl.prototype.goToSuggestion = function (iDiff /*:int*/) {
    var cSuggestionNodes = this.layer.childNodes;
    
    if (cSuggestionNodes.length > 0) {
        var oNode = null;
    
        if (iDiff > 0) {
            if (this.cur < cSuggestionNodes.length-1) {
                oNode = cSuggestionNodes[++this.cur];
            }        
        } else {
            if (this.cur > 0) {
                oNode = cSuggestionNodes[--this.cur];
            }    
        }
        
        if (oNode) {
            this.highlightSuggestion(oNode);
            this.textbox.value = oNode.firstChild.nodeValue;
        }
    }
};

/**
 * Handles three keydown events.
 * @scope private
 * @param oEvent The event object for the keydown event.
 */
AutoSuggestControl.prototype.handleKeyDown = function (oEvent /*:Event*/) {

    switch(oEvent.keyCode) {
        case 38: //up arrow
            this.goToSuggestion(-1);
            break;
        case 40: //down arrow 
            this.goToSuggestion(1);
            break;
        case 27: //esc
            this.textbox.value = this.userText;
            this.selectRange(this.userText.length, 0);
            /* falls through */
        case 13: //enter
            this.hideSuggestions();
            eleName = this.textbox.name;
			if (eleName == 'studentRollNo'){
			
				getStudent(this.textbox.value,'Add');
			}
            eleNameArray = eleName.split('_');
            if (eleNameArray[0] == 'voucherLedgers'){
                ctr = parseInt(eleNameArray[1]);
                var form = document.voucherForm;
                var debitDisabled = eval('form.debit_'+ctr+'.disabled');
                var creditDisabled = eval('form.credit_'+ctr+'.disabled');
                if (debitDisabled == true) {
                    eleName = 'credit_'+ctr;
                }
                else {
                    eleName = 'debit_'+ctr;
                }
                eval('form.'+eleName+'.focus()');
            }
            oEvent.returnValue = false;
            if (oEvent.preventDefault) {
                oEvent.preventDefault();
            }
            break;
    }

};

/**
 * Handles keyup events.
 * @scope private
 * @param oEvent The event object for the keyup event.
 */
AutoSuggestControl.prototype.handleKeyUp = function (oEvent /*:Event*/) {

    var iKeyCode = oEvent.keyCode;
    var oThis = this;
    
    //get the currently entered text
    this.userText = this.textbox.value;
    
    clearTimeout(this.timeoutId);

    //for backspace (8) and delete (46), shows suggestions without typeahead
    if (iKeyCode == 8 || iKeyCode == 46) {
        
        this.timeoutId = setTimeout( function () {
            oThis.provider.requestSuggestions(oThis, false);
        }, 250);
        
    //make sure not to interfere with non-character keys
    } else if (iKeyCode < 32 || (iKeyCode >= 33 && iKeyCode < 46) || (iKeyCode >= 112 && iKeyCode <= 123)) {
        //ignore
    } else {
        //request suggestions from the suggestion provider with typeahead
        this.timeoutId = setTimeout( function () {
            oThis.provider.requestSuggestions(oThis, true);
        }, 250);
    }
};

/**
 * Hides the suggestion dropdown.
 * @scope private
 */
AutoSuggestControl.prototype.hideSuggestions = function () {
    this.layer.style.visibility = "hidden";
};

/**
 * Highlights the given node in the suggestions dropdown.
 * @scope private
 * @param oSuggestionNode The node representing a suggestion in the dropdown.
 */
AutoSuggestControl.prototype.highlightSuggestion = function (oSuggestionNode) {
    
    for (var i=0; i < this.layer.childNodes.length; i++) {
        var oNode = this.layer.childNodes[i];
        if (oNode == oSuggestionNode) {
            oNode.className = "current"
        } else if (oNode.className == "current") {
            oNode.className = "";
        }
    }
};

/**
 * Initializes the textbox with event handlers for
 * auto suggest functionality.
 * @scope private
 */
AutoSuggestControl.prototype.init = function (task) {
    //save a reference to this object
    var oThis = this;
    
    //assign the onkeyup event handler
    this.textbox.onkeyup = function (oEvent) {
    
        //check for the proper location of the event object
        if (!oEvent) {
            oEvent = window.event;
        }    
        
        //call the handleKeyUp() method with the event object
        oThis.handleKeyUp(oEvent);
    };
    
    //assign onkeydown event handler
    this.textbox.onkeydown = function (oEvent) {

        //check for the proper location of the event object
        if (!oEvent) {
            oEvent = window.event;
        }    
        
        //call the handleKeyDown() method with the event object
        oThis.handleKeyDown(oEvent);
    };
    
    //assign onblur event handler (hides suggestions)    
    this.textbox.onblur = function () {
        oThis.hideSuggestions();
    };
    
    //create the suggestions dropdown
    this.createDropDown(task);
};

/**
 * Selects a range of text in the textbox.
 * @scope public
 * @param iStart The start index (base 0) of the selection.
 * @param iEnd The end index of the selection.
 */
AutoSuggestControl.prototype.selectRange = function (iStart /*:int*/, iEnd /*:int*/) {

    //use text ranges for Internet Explorer
    if (this.textbox.createTextRange) {
        var oRange = this.textbox.createTextRange(); 
        oRange.moveStart("character", iStart); 
        oRange.moveEnd("character", iEnd - this.textbox.value.length);      
        oRange.select();
        
    //use setSelectionRange() for Mozilla
    } else if (this.textbox.setSelectionRange) {
        this.textbox.setSelectionRange(iStart, iEnd);
    }     

    //set focus back to the textbox
    this.textbox.focus();      
}; 

/**
 * Builds the suggestion layer contents, moves it into position,
 * and displays the layer.
 * @scope private
 * @param aSuggestions An array of suggestions for the control.
 */
AutoSuggestControl.prototype.showSuggestions = function (aSuggestions, task /*:Array*/) {
    
    var oDiv = null;
    this.layer.innerHTML = "";  //clear contents of the layer

    for (var i=0; i < aSuggestions.length; i++) {
        oDiv = document.createElement("div");
        if (task == 'ledgerGroups') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['groupName']));
        }
        else if (task == 'companyGroups') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['groupName']));
        }
        else if (task == 'rollNumber' || task == 'fineRollNumber') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['rollNo']));
        }
        else if (task == 'receiptVoucherDr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'receiptVoucherCr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'paymentVoucherDr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'paymentVoucherCr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'journalVoucherDr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'journalVoucherCr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'contraVoucherDr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        else if (task == 'contraVoucherCr') {
            oDiv.appendChild(document.createTextNode(aSuggestions[i]['ledgerName']));
        }
        this.layer.appendChild(oDiv);
    }
    
    this.layer.style.left = this.getLeft() + 5 + "px";
    this.layer.style.top = (this.getTop()+this.textbox.offsetHeight + 5) + "px";
    if (task == 'receiptVoucherDr' || task == 'receiptVoucherCr' || task == 'paymentVoucherDr' || task == 'paymentVoucherCr' || task == 'journalVoucherDr' || task == 'journalVoucherCr' || task == 'contraVoucherDr' || task == 'contraVoucherCr') {
        this.layer.style.left = this.getLeft() + 0 + "px";
        this.layer.style.top = (this.getTop()+this.textbox.offsetHeight + 0) + "px";
    }
    this.layer.style.visibility = "visible";

};

/**
 * Inserts a suggestion into the textbox, highlighting the 
 * suggested part of the text.
 * @scope private
 * @param sSuggestion The suggestion for the textbox.
 */
AutoSuggestControl.prototype.typeAhead = function (sSuggestion /*:String*/) {

    //check for support of typeahead functionality
    if (this.textbox.createTextRange || this.textbox.setSelectionRange){
        var iLen = this.textbox.value.length; 
        this.textbox.value = sSuggestion; 
        this.selectRange(iLen, sSuggestion.length);
    }
};

/**
 * Provides suggestions for state/province names.
 * @class
 * @scope public
 */
function SuggestionProvider() {
    this.http = zXmlHttp.createRequest();
}

/**
 * Request suggestions for the given autosuggest control. 
 * @scope protected
 * @param oAutoSuggestControl The autosuggest control to provide suggestions for.
 */
SuggestionProvider.prototype.requestSuggestions = function (oAutoSuggestControl /*:AutoSuggestControl*/,
                                                            bTypeAhead /*:boolean*/) {

    var oHttp = this.http;
                                   
    //cancel any active requests                          
    if (oHttp.readyState != 0) {
        oHttp.abort();
    }                 
    
    //define the data
    var oData = 'requesting='+oAutoSuggestControl.task+'&text='+oAutoSuggestControl.userText;
    if (oAutoSuggestControl.task == 'ledgerGroups') {
        thisGroupArray = new Array();
         var len=ledgerGroupArray.length;
         var str='';
         var reg=new RegExp('^'+oAutoSuggestControl.userText.toLowerCase());
         for (x=0; x< len; x++){
             thisGroupName = ledgerGroupArray[x]['groupName'].toLowerCase();
             if (thisGroupName.match(reg)) {
                 thisGroupArray.push(ledgerGroupArray[x]);
             }
         }
         oAutoSuggestControl.autosuggest(thisGroupArray, bTypeAhead, oAutoSuggestControl.task);
    }
    else if (oAutoSuggestControl.task == 'receiptVoucherDr' || oAutoSuggestControl.task == 'paymentVoucherDr' || oAutoSuggestControl.task == 'journalVoucherDr' || oAutoSuggestControl.task == 'contraVoucherDr') {
        thisGroupArray = new Array();
         var len=debitLedgerArray.length;
         var str='';
         var reg=new RegExp('^'+oAutoSuggestControl.userText.toLowerCase());
         for (x=0; x< len; x++){
             thisGroupName = debitLedgerArray[x]['ledgerName'].toLowerCase();
             if (thisGroupName.match(reg)) {
                 thisGroupArray.push(debitLedgerArray[x]);
             }
         }
         oAutoSuggestControl.autosuggest(thisGroupArray, bTypeAhead, oAutoSuggestControl.task);
    }
    else if (oAutoSuggestControl.task == 'receiptVoucherCr' || oAutoSuggestControl.task == 'paymentVoucherCr' || oAutoSuggestControl.task == 'journalVoucherCr' || oAutoSuggestControl.task == 'contraVoucherCr') {
        thisGroupArray = new Array();
         var len=creditLedgerArray.length;
         var str='';
         var reg=new RegExp('^'+oAutoSuggestControl.userText.toLowerCase());
         for (x=0; x< len; x++){
             thisGroupName = creditLedgerArray[x]['ledgerName'].toLowerCase();
             if (thisGroupName.match(reg)) {
                 thisGroupArray.push(creditLedgerArray[x]);
             }
         }
         oAutoSuggestControl.autosuggest(thisGroupArray, bTypeAhead, oAutoSuggestControl.task);
    }
    else if (oAutoSuggestControl.task == 'companyGroups') {
        oHttp.open("post", libraryPath + "/suggestions.php", true);
        oHttp.onreadystatechange = function () {
            if (oHttp.readyState == 4) {
                //evaluate the returned text JavaScript (an array)
                var aSuggestions = JSON.parse(oHttp.responseText);

                //provide suggestions to the control
                oAutoSuggestControl.autosuggest(aSuggestions, bTypeAhead, oAutoSuggestControl.task);
            }    
        };
        //send the request
        oHttp.send(oData);
    }
      else if (oAutoSuggestControl.task == 'rollNumber' || oAutoSuggestControl.task == 'fineRollNumber') {
        oHttp.open("post", libraryPath + "/suggestions.php?"+oData, true);
        oHttp.onreadystatechange = function () {
            if (oHttp.readyState == 4) {
                //evaluate the returned text JavaScript (an array)
                var aSuggestions = JSON.parse(oHttp.responseText);

                //provide suggestions to the control
                oAutoSuggestControl.autosuggest(aSuggestions, bTypeAhead, oAutoSuggestControl.task);
            }    
        };
        //send the request
        oHttp.send(oData);
    }

};