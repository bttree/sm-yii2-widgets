function initSlugFields(sourceFieldSelector, targetFieldSelector, updateUrl) {
    var timer,
        sourceField = $(sourceFieldSelector),
        targetField = $(targetFieldSelector),
        editable = targetField.val().length == 0,
        value = sourceField.val();

    if (targetField.val().length !== 0) {
        var data = {};
        data[sourceField.attr('name')] = sourceField.val();

        $.post(updateUrl, data, function (result) {
            editable = targetField.val() == result.transliteration;
        });
    }

    sourceField.on('keyup blur copy paste cut start', function () {
        clearTimeout(timer);

        if (editable && value != sourceField.val()) {
            timer = setTimeout(function () {
                value = sourceField.val();
                targetField.attr('disabled', 'disabled');

                var data = {};
                data[sourceField.attr('name')] = sourceField.val();

                $.post(updateUrl, data, function (result) {
                    targetField.val(result.transliteration).removeAttr('disabled');
                });
            }, 300);
        }
    });

    targetField.on('change', function () {
        editable = $(this).val().length == 0;
    });
}