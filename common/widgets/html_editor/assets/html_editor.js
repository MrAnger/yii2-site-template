function htmlEditorInit(el, data) {
    new Vue({
        el: el,
        data: function () {
            return $.extend({
                selectedType: null,
                content: null,
                nameAttribute: null,

                aceEditorInstance: null,
                ckEditorInstance: null,

                editorList: {}
            }, data);
        },

        mounted: function () {
            var vm = this;

            // Получаем выбранный по умолчанию тип редактора
            vm.selectedType = $(vm.$el).find('input[name*="source-view"]:checked').val();

            // Подписываемся на событие изменения выбранного редактора
            $(vm.$el).find('input[name*="source-view"]').change(function (e) {
                vm.selectedType = $(this).val();
            });

            // Получаем текущее значение из редактора по умолчанию
            vm.content = $(vm.$el).find(vm.editorList[vm.selectedType].input).val();

            // Получаем атрибут NAME из редактора по умолчанию
            vm.nameAttribute = $(vm.$el).find(vm.editorList[vm.selectedType].input).attr('name');

            // Из всех редакторов убираем атрибут NAME, что бы при сабмите формы не передавалось оба значения
            $.each(vm.editorList, function (key, data) {
                $(vm.$el).find(data.input).removeAttr('name');
            });

            // Возвращаем атрибут NAME активному редактору
            $(vm.$el).find(vm.editorList[vm.selectedType].input).attr('name', vm.nameAttribute);

            // Подписываемся на изменение значение AceEditor и получаем его инстанс
            setTimeout(function () {
                var aceEditorId = $(vm.$el).find(vm.editorList["html-editor"].wrapper).children('.ace_editor').attr('id'),
                    aceEditor = ace.edit(aceEditorId);

                vm.aceEditorInstance = aceEditor;

                aceEditor.getSession().on('change', function () {
                    vm.content = aceEditor.getSession().getValue();
                });
            }, 1500);

            // Подписываемся на изменение значение CKEditor и получаем его инстанс
            setTimeout(function () {
                vm.ckEditorInstance = CKEDITOR.instances[$(vm.$el).find(vm.editorList["wysiwyg-editor"].input).attr('id')];

                $(vm.$el).find(vm.editorList["wysiwyg-editor"].input).change(function (e) {
                    vm.content = this.value;
                });
            }, 1500);
        },

        watch: {
            selectedType: function (selectedType) {
                var vm = this;

                // Скрываем все редакторы
                $.each(vm.editorList, function (key, data) {
                    $(vm.$el).find(data.wrapper).hide();
                });

                // Из всех редакторов убираем атрибут NAME, что бы при сабмите формы не передавалось оба значения
                $.each(vm.editorList, function (key, data) {
                    $(vm.$el).find(data.input).removeAttr('name');
                });

                // Отображаем только нужный редактор
                $(vm.$el).find(vm.editorList[selectedType].wrapper).show();

                // Возвращаем атрибут NAME активному редактору
                $(vm.$el).find(vm.editorList[vm.selectedType].input).attr('name', vm.nameAttribute);

                // Задаем актуальное значение при приключении редактора, а так же фокус
                switch (vm.selectedType) {
                    case 'html-editor':
                        if (vm.aceEditorInstance) {
                            vm.aceEditorInstance.getSession().setValue(vm.content);

                            setTimeout(function () {
                                vm.aceEditorInstance.focus();
                            }, 500);
                        }
                        break;
                    case 'wysiwyg-editor':
                        if (vm.ckEditorInstance) {
                            vm.ckEditorInstance.setData(vm.content);

                            setTimeout(function () {
                                vm.ckEditorInstance.focus();
                            }, 500);
                        }
                        break;
                }
            }
        }
    });
}