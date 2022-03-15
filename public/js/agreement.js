class Agreement {

    /** @type {HTMLFormElement} */
    form;

    /** @type {HTMLButtonElement} */
    button;

    /** @type {Modal} */
    modal = null;

    constructor() {

        this.modal = new Modal();

        /*
        this.button = document.querySelector('button[name="agreement_form[submit]"]');
        this.button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            alert('ajax validation!');
        });
         */

        this.form = document.querySelector('form[name="agreement_form"]');
        this.form.addEventListener('submit', (event) => {
            event.preventDefault();

            this.check();
            //
        });
    }

    check() {
        let from = document.querySelector('#agreement_form_date_start_year').value;
        from += '-' + document.querySelector('#agreement_form_date_start_month').value;
        from += '-' + document.querySelector('#agreement_form_date_start_day').value;

        let to = document.querySelector('#agreement_form_date_end_year').value;
        to += '-' + document.querySelector('#agreement_form_date_end_month').value;
        to += '-' + document.querySelector('#agreement_form_date_end_day').value;

        let agreement = document.querySelector('#agreement_form_id').value;
        let property = document.querySelector('#agreement_form_property').value;

        let formData = new URLSearchParams();
        formData.append('from', from);
        formData.append('to', to);
        formData.append('property', property);
        formData.append('agreement', agreement);

        let fetchData = {
            method: 'POST',
            body: formData
        }

        let url = '/agreement/check';
        fetch(url, fetchData)
            .then((res) => {
                if (res.status >= 200 && res.status < 300) {
                    return res;
                } else {
                    let error = new Error(res.statusText);
                    error.response = res;
                    throw error
                }
            })
            .then(res => res.json())
            .then((data) => {
                console.log(data);
                if (data.success && (parseInt(data.success) === 1)) {
                    this.form.submit();
                } else if (data.error && (data.error.length > 0)) {
                    this.modalMessage('Error', data.error);
                } else if (data.overlap && (data.overlap.length > 0)) {
                    this.modalOverlap(data.overlap);
                }
            })
            .catch((e) => {
                console.log('error: ' + e.message);
                console.log(e.response);
            });
    }

    modalOverlap(items) {
        let h2 = document.createElement('h2');
        h2.innerHTML = 'Overlapping by dates';

        let p1 = document.createElement('p');
        p1.innerHTML = 'This property already has a agreement overlapping by dates';

        let text = '<table border="1">';
        text += '<tr>';
        text += '<td>id</td>';
        text += '<td>identificator</td>';
        text += '<td>tenant</td>';
        text += '<td>dates</td>';
        text += '</tr>';
        for (let i = 0; i < items.length; i++) {
            let item = items[i];
            text += '<tr>';
            text += '<td>' + item.id + '</td>';
            text += '<td>' + item.identificator + '</td>';
            text += '<td>' + item.tenant + '</td>';
            text += '<td>' + item.dates + '</td>';
            text += '</tr>';
        }
        text += '</table>';

        let divItems = document.createElement('div');
        divItems.innerHTML = text;

        let buttonCancel = document.createElement('a');
        buttonCancel.innerHTML = 'Cancel'
        buttonCancel.setAttribute('href', '#');
        buttonCancel.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.modal.close();
        });

        let buttonSave = document.createElement('a');
        buttonSave.innerHTML = 'Save anyway'
        buttonSave.setAttribute('href', '#');
        buttonSave.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.modal.close();
            this.form.submit();
        });

        let divTop = document.createElement('div');
        divTop.classList.add('modal-top');
        divTop.append(h2);
        divTop.append(p1);
        divTop.append(divItems);

        let divBottom = document.createElement('div');
        divBottom.classList.add('modal-message-bottom');
        divBottom.append(buttonCancel);
        divBottom.append(buttonSave);

        let div = document.createElement('div');
        div.classList.add('modal');
        div.classList.add('modal-message');
        div.append(this.modal.getButtonClose());
        div.append(divTop);
        div.append(divBottom);

        this.modal.show(div);

        return true;
    }

    modalMessage(headline, text) {

        let h2 = document.createElement('h2');
        h2.innerHTML = headline;

        let p1 = document.createElement('p');
        p1.innerHTML = text;

        let buttonClose = document.createElement('a');
        buttonClose.innerHTML = 'Close'
        buttonClose.classList.add('button-action');
        buttonClose.classList.add('button-action__orange');
        buttonClose.classList.add('modal-button__close-after-message');
        buttonClose.setAttribute('href', '#');
        buttonClose.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.modal.close();
        });

        let divTop = document.createElement('div');
        divTop.append(h2);
        divTop.append(p1);

        let divBottom = document.createElement('div');
        divBottom.append(buttonClose);

        let div = document.createElement('div');
        div.classList.add('modal');
        div.classList.add('modal-message');
        div.append(this.modal.getButtonClose());
        div.append(divTop);
        div.append(divBottom);

        this.modal.show(div);

        return true;
    }
}

class Modal {

    /** @type {HTMLElement} */
    background = null;

    /** @type {HTMLElement} */
    wrapper = null;

    constructor() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('modal-wrapper');
        this.wrapper.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        this.background = document.createElement('div');
        this.background.classList.add('modal-background');
        this.background.append(this.wrapper);
        this.background.addEventListener('click', (e) => {
            this.close();
        });
    }

    create() {
        if (!document.querySelector('modal-background')) {
            let body = document.getElementsByTagName('body')[0];
            body.append(this.background);
        }
    }

    show(html) {
        this.create();
        this.wrapper.innerHTML = '';
        this.wrapper.append(html);
    }

    close() {
        this.background.remove();
    }

    /**
     * @returns {HTMLAnchorElement}
     */
    getButtonClose() {
        let buttonClose = document.createElement('a');
        buttonClose.classList.add('modal-button__close');
        buttonClose.setAttribute('href', '#');
        buttonClose.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.close();
        });
        return buttonClose;
    }
}

window.addEventListener('DOMContentLoaded', () => {
    new Agreement();
});