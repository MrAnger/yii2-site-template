(function () {
    $.format.locale({
        number: {
            groupingSeparator: ' ',
            decimalSeparator: ','
        }
    });
})();

function getCaretPosition(ctrl) {
    // IE < 9 Support
    if (document.selection) {
        ctrl.focus();
        var range = document.selection.createRange();
        var rangelen = range.text.length;
        range.moveStart('character', -ctrl.value.length);
        var start = range.text.length - rangelen;
        return {'start': start, 'end': start + rangelen};
    }
    // IE >=9 and other browsers
    else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
        return {'start': ctrl.selectionStart, 'end': ctrl.selectionEnd};
    } else {
        return {'start': 0, 'end': 0};
    }
}

function setCaretPosition(ctrl, start, end) {
    // IE >= 9 and other browsers
    if (ctrl.setSelectionRange) {
        ctrl.focus();
        ctrl.setSelectionRange(start, end);
    }
    // IE < 9
    else if (ctrl.createTextRange) {
        var range = ctrl.createTextRange();
        range.collapse(true);
        range.moveEnd('character', end);
        range.moveStart('character', start);
        range.select();
    }
}

// Скрипт позволяющий вводить только цены в поля для ввода текста
$(document).on('input', '.price-input', function (e) {
    var $el = $(this),
        value = $el.val(),
        caretPosition = getCaretPosition(this);

    // Заменяем запятую на точку
    if (/,/.test(value)) {
        // Если запятная одна, то увеличиваем значения позиции каретки, для визуально правильной работы
        if (value.indexOf(',') == value.lastIndexOf(',') && value.indexOf('.') == -1) {
            caretPosition.start++;
            caretPosition.end++;
        }

        value = value.replace(/,/ig, '.');
    }

    // Убираем лишние ненужные символы
    if (/[^0-9\.]/.test(value)) {
        value = value.replace(/[^0-9\.]/ig, '');
    }

    // Проверяем, сколько точек в инпуте, если больше одной, то оставляем первую, а остальные убираем
    if (value.indexOf('.') != value.lastIndexOf('.')) {
        var firstPart = value.substring(0, value.indexOf('.')),
            secondPart = value.substring(value.indexOf('.')).replace(/[^0-9]/ig, '');

        value = firstPart + "." + secondPart;
    }

    // Оставляем только два знака после числа
    if (value.indexOf('.') != -1 && value.substring(value.indexOf('.') + 1).length > 2) {
        value = value.match(/[^\.]*\.\d{2}/);
    }

    if ($el.val() != value) {
        $el.val(value);

        setCaretPosition(this, caretPosition.start - 1, caretPosition.end - 1);
    }
});

// Скрипт позволяющий вводить только целые числа в поля для ввода текста
$(document).on('input', '.integer-input', function (e) {
    var $el = $(this),
        value = $el.val(),
        caretPosition = getCaretPosition(this);

    // Убираем лишние ненужные символы
    if (/[^0-9]/.test(value)) {
        $el.val(value.replace(/[^0-9]/ig, ''));

        setCaretPosition(this, caretPosition.start - 1, caretPosition.end - 1);
    }
});