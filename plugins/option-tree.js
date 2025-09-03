$(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Initialize Select2 Elements
    $(".select2bs4").select2({
        theme: "bootstrap4",
    });
});
$(document).ready(function () {
    function createNode(level = 0, data = null) {
        const node = $('<div class="flow-node"></div>');
        const select = $(`<select multiple class="flow-select" style="width:100%"></select>`);
        const childrenMap = {};

        node.append(select);

        select.select2({
            tags: true,
            placeholder: 'Enter options...',
            width: 'style'
        });

        if (data && Array.isArray(data)) {
            data.forEach(item => {
                if (item.name) {
                    const option = new Option(item.name, item.name, true, true);
                    select.append(option).trigger('change');
                }
            });
        }

        select.on('select2:select', function (e) {
            const val = e.params.data.id;
            if (!childrenMap[val]) {
                // Create wrapper that holds label + child block
                const wrapper = $('<div class="tree-node-wrapper mt-2"></div>');
                const label = $(`<b>${val}</b>`);

                const stopBtn = $('<button type="button" class="stop-btn btn btn-danger btn-sm ml-2">Stop</button>');
                const stopInput = $('<input type="text" class="stop-text form-control mt-2" placeholder="Enter stop message..." style="display:none;">');

                stopBtn.on('click', function (e) {
                    e.preventDefault();
                    childContainer.find('.flow-node').hide();
                    stopInput.show();
                    stopBtn.hide();
                });

                const childContainer = $('<div class="child-container"></div>');
                const childNode = createNode(level + 1);
                childContainer.append(stopBtn).append(stopInput).append(childNode.container);

                wrapper.append(label).append(childContainer);
                node.append(wrapper);

                childrenMap[val] = {
                    wrapper: wrapper,
                    container: childContainer,
                    childNode: childNode,
                    stopInput: stopInput
                };
            }
        });


        select.on('select2:unselect', function (e) {
            const val = e.params.data.id;
            if (childrenMap[val]) {
                childrenMap[val].wrapper.remove();  // remove the full wrapper (label + content)
                delete childrenMap[val];
            }
        });


        return {
            container: node,
            getData: function () {
                const selected = select.val() || [];
                return selected.map(val => {
                    const child = childrenMap[val];
                    if (child.stopInput.is(':visible')) {
                        return {
                            name: val,
                            children: [{
                                stop: true,
                                stop_message: child.stopInput.val()
                            }]
                        };
                    } else {
                        return {
                            name: val,
                            children: child.childNode.getData()
                        };
                    }
                });
            },
            setData: function (dataArray) {
                dataArray.forEach(item => {
                    if (select.find('option[value="' + item.name + '"]').length === 0) {
                        const option = new Option(item.name, item.name, true, true);
                        select.append(option);
                    }
                    select.val((select.val() || []).concat(item.name)).trigger('change');
                    select.trigger({
                        type: 'select2:select',
                        params: {
                            data: {
                                id: item.name
                            }
                        }
                    });

                    if (item.children && item.children.length > 0) {
                        const isStopped = item.children[0].stop;
                        const child = childrenMap[item.name];
                        if (isStopped) {
                            child.stopInput.val(item.children[0].stop_message).show();
                            child.container.find('.flow-node').hide();
                            child.container.find('.stop-btn').hide();
                        } else {
                            child.childNode.setData(item.children);
                        }
                    }
                });
            }
        };
    }

    // Initialize tree
    const rootNode = createNode();
    $('#flow-container').append(rootNode.container);

    // Save button handler
    $('#save-options').on('click', function (e) {
        e.preventDefault();
        const json = rootNode.getData();
        const title = $('#tree-title').val();
        const customerId = $('#customer-id').val();
        const intro = $('#intro').val();

        $.ajax({
            url: 'save-options.php',
            method: 'POST',
            data: {
                id: $('#option-tree-form input[name="id"]').val() || '',
                title: title,
                customer_id: customerId,
                intro: intro,
                tree: JSON.stringify(json)
            },
            success: function () {
                alert("Tree saved successfully!");
            },
            error: function (err) {
                console.error(err);
                alert("Failed to save tree.");
            }
        });
    });

    // Optional: Load existing data if editing
    rootNode.setData(treeData);
});