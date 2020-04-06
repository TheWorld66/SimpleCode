window.onload = function() {
    var field = document.querySelectorAll('[fieldName]');
    for(var i = 0; i < field.length; i++) {
        field[i].onclick = handleOnclick.bind(this, field[i]);
    }
}

function handleOnblur(input, field) {
    var fieldName = field.getAttribute('fieldName');
    var value = input.value
    field.innerHTML = value;
    field.onclick = handleOnclick.bind(this, field);
    makeRequest(
        "PUT", 
        document.querySelector('[handleServer]').getAttribute('handleServer'), 
        { 
            [fieldName]: value,
            id: field.parentElement.getAttribute('server-id')
        }
    ).then(function(response) {
        field.parentElement.querySelector('[fieldName=updated_at]').innerHTML = (JSON.parse(response)).updated_at;
    });
}

function handleOnclick(field) {
    var fieldName = field.getAttribute('fieldName');
    //status should be a select the others are inputs
    if(fieldName == 'status') {
        //remove wierd white space from the value
        var input = generateSelect(field.innerHTML.replace(/[\n\r]/g, '').replace(/ /g,''));
        field.onclick = function(){return false;};
        field.innerHTML = '';
        field.appendChild(input);
    } else if(fieldName != 'created_at' && fieldName != 'updated_at'){
        //remove wierd white space from the value
        var input = generateInput(fieldName, field.innerHTML.replace(/[\n\r]/g, '').replace(/ /g,''));
        field.onclick = function(){return false;};
        field.innerHTML = '';
        field.appendChild(input);
    }
    input.onblur = handleOnblur.bind(this, input, field);
    input.focus();
}

function handleSave(_this) {
    //2 levels since we want the whole row not just the column
    var row = _this.parentElement.parentElement;
    var field = row.querySelectorAll('[name]');
    var payload = {};
    for(var i = 0; i < field.length; i++) {
        var fieldName = field[i].getAttribute('name');
        payload[fieldName] = field[i]? field[i].value : '';
    }
    payload.id = '-1';
    makeRequest(
        'POST', 
        document.querySelector('[handleServer]').getAttribute('handleServer'),
        payload
    ).then(function(response) {
        //clear inputs
        for(var i = 0; i < field.length; i++) {
            if(field[i]) {
                if(field[i].getAttribute('name') == 'status')
                    field[i].value = 'Up';
                else
                    field[i].value = '';
            }
        }

        //clone previous row and add the new values
        var data = JSON.parse(response);
        var clone = row.previousElementSibling.cloneNode(true);
        row.parentElement.insertBefore(clone,row);
        clone.setAttribute('server-id', data.id);
        clone.style.display = 'flex';
        field = clone.querySelectorAll('[fieldName]');
        for(var i = 0; i < field.length; i++) {
            field[i].innerHTML = data[field[i].getAttribute('fieldName')];
        }
    });
}

function handleDelete(_this) {
    //this is a really simple confirmation
    var answer = confirm("Are you sure you want to delete this server?");
    if(answer) {
        var row = _this.parentElement.parentElement;
        makeRequest(
            'DELETE', 
            document.querySelector('[handleServerDelete]').getAttribute('handleServerDelete'),
            {id: row.getAttribute('server-id')}
        ).then(function(response) {
            console.dir(response);
            row.parentElement.removeChild(row);
        });
    }
}

function generateInput(fieldName, value) {
    var input = document.createElement('input');
    input.classList.add('form-control');
    input.value = value;
    if(fieldName = 'ipv4') {
        input.placeholder = 'XXX.XXX.XXX.XXX';
        input.setAttribute('maxlength', '15')
    }
    else {
        input.setAttribute('maxlength', '50')
        input.placeholder = 'max length: 50';
    }
    
    return input;
}

function generateSelect(value) {
    var select = document.createElement('select');
    var option = document.createElement('option');
    option.textContent = 'Up';
    option.value = 'Up';
    select.appendChild(option); 
    var option = document.createElement('option');
    option.appendChild( document.createTextNode('Down') );
    option.value = 'Down';
    select.appendChild(option);
    var option = document.createElement('option'); 
    option.appendChild( document.createTextNode('Maintenance') );
    option.value = 'Maintenance';
    select.appendChild(option);
    select.classList.add('form-control');
    select.querySelector('option[value="' + value + '"]').selected = true;
    return select;
}

function makeRequest(method, url, payload) {
    //custom ajax promise
    return new Promise( function(resolve, reject) {
        var xhttp = new XMLHttpRequest();
        xhttp.open(method, url);
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhttp.setRequestHeader('Content-Type', 'application/json');
        xhttp.onload = function () {
            if (this.status >= 200 && this.status < 300) {
              resolve(xhttp.response);
            } else {
                alert('An error has occured. Please check the debugger tool in order to have more details');
                console.dir({
                    status: this.status,
                    statusText: xhttp.statusText
                });
                //not really handled
                reject({
                    status: this.status,
                    statusText: xhttp.statusText,
                    response: xhttp.response
                });
            }
        };
        xhttp.send(JSON.stringify(payload));
    });
}