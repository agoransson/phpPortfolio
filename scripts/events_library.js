/*
 * This libarary contains useful functions to use in replacment for
 * those specified in DOM. Using function detection the functions in 
 * this library make use of the functions specified in DOM or other 
 * functions implemented by browser that isn't DOM compliant.
 * 
 * Compatible browsers:
 * DOM-standard (DOM)
 * Mozilla/Firefox (DOM)
 * Internet Explorer (IE)
 */

/*
 * Creating an eventlistner.
 *
 * eventElement: The element to wich to attach the event
 * eventType: the event type for wich to trigger
 * eventFunction: the function that is to run if the event triggers
 * useCapture: true if the eventlistner should be a capturing listner
 *             otherwise false (DOM only)
 *
 * return: true/false depending on returnvalue from IE function attachEvent
 *         always true for Mozilla or just ignore returnvalue
 */
function addEvent(eventElement, eventType, eventFunction, useCapture) {
  if(eventElement.addEventListener){ //DOM
    eventElement.addEventListener(eventType, eventFunction, useCapture);
    return true;
  }
  else if(eventElement.attachEvent){//IE
    var returnvalue = eventElement.attachEvent("on"+eventType, eventFunction);
    return returnvalue;
  }
}

/*
 * Removing an eventlistner.
 *
 * eventElement: The element that is to have a listner removed
 * eventType: the event type that is to be removed
 * eventFunction: the function that is to be removed
 * useCapture: true if the eventlistner is a capturing listner
 *             otherwise false (DOM only)
 */
function removeEvent(eventElement, eventType, eventFunction, useCapture) {
  if(eventElement.removeEventListener){ //DOM
    eventElement.removeEventListener(eventType, eventFunction, useCapture);
  }
  else if(eventElement.detachEvent){//IE
    eventElement.detachEvent("on"+eventType, eventFunction);
  }
}

/*
 * Returns the eventobject if such exist.
 * 
 * eventObject: the eventparameter from the eventfunction. This may contain
 *        an eventObject reference already if Mozilla is used.
 *
 * return: The event objekt if such exists otherwise null.
 */
function getEventObject(eventObject){
  if(eventObject){ //DOM
    return eventObject;
  }
  else if(window.event){//IE
    return window.event;
  }
  else{
    return null;
  }
}

/*
 * Returns the eventtarget given an eventobject.
 *
 * eventObject: a reference to an eventobject
 *
 * return: The event target if such exists otherwise null.
 */
function getEventTarget(eventObject){
  if(eventObject.target){ //DOM
    return eventObject.target;
  }
  else if(eventObject.srcElement){ //IE
    return eventObject.srcElement;
  }
  else{
    return null;
  }
}

/*
 * Prevent default actions to occur
 *
 * eventObject: the event object that is passed as an parameter
 *              to the eventfunction in DOM.
 */
function preventDefaultAction(eventObject){
  if(eventObject.preventDefault){ //DOM
    eventObject.preventDefault();
  }
  if(window.event){ //IE
    window.event.returnValue = false;
  }
}

/*
 * Stops the propagation of the event in the bubling fase.
 *
 * eventObject: the event object that is passed as an parameter
 *              to the eventfunction in DOM.
 */
function stopBubbling(eventObject){
  if(eventObject.stopPropagation){ //DOM
    eventObject.stopPropagation();
  }
  else if(window.event){ //IE
    window.event.cancelBubble = true;
  }
}


