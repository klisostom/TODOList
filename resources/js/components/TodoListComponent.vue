<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>TODO List</h1>

                        <div class="input-group input-group-lg">
                            <input
                                type="text"
                                class="form-control"
                                aria-label="Large"
                                aria-describedby="inputGroup-sizing-sm"
                                autofocus="autofocus"
                                autocomplete="off"
                                placeholder="O que você precisa fazer?"
                                v-model="newTodo"
                                @keyup.enter="addTodo"
                            />
                        </div>
                    </div>
                    <div class="card-body">
                        <ul
                            v-for="item in todos"
                            :key="item.id"
                            class="list-group list-group-flush todo-list"
                        >
                            <li class="list-group-item">
                                <div>
                                    <input
                                        type="checkbox"
                                        v-model="item.completed"
                                    />

                                    <label
                                        v-if="!item.edited"
                                        @dblclick="editTodo(item)"
                                        data-placement="top"
                                        todo="Clique para editar"
                                        :class="{ completed: item.completed }"
                                        >{{ item.todo }}
                                    </label>

                                    <input
                                        v-else
                                        type="text"
                                        class="todo-list-edit edit"
                                        v-model="item.todo"
                                        @blur="doneEdit(item)"
                                        @keyup.enter="doneEdit(item)"
                                        @keyup.esc="cancelEdit(item)"
                                        v-focus
                                    />

                                    <button
                                        type="button"
                                        class="close"
                                        aria-label="Close"
                                        @click="removeTodo(item.id, item)"
                                    >
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer container-extra">
                        <div>
                            <label for="checkall">
                                <input
                                    type="checkbox"
                                    name="checkall"
                                    :checked="!anyRemaining"
                                    @change="checkAllTodos"
                                />
                                Marcar Todos
                            </label>
                        </div>
                        <div>{{ remaining }} itens restantes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "todo-list",
    data() {
        return {
            newTodo: "",
            beforeEditCache: "",

            resource: this.$resource("http://127.0.0.1:8000/api/tarefas"),
            todos: []
        };
    },
    computed: {
        remaining() {
            return this.todos.filter(todo => !todo.completed).length;
        },
        anyRemaining() {
            return this.remaining !== 0;
        }
    },
    directives: {
        focus: {
            inserted: function(el) {
                el.focus();
            }
        }
    },
    methods: {
        initialize() {
            this.resource.get({}).then(response => {
                this.todos = response.data.map(item => {
                    return {
                        id: item.id,
                        todo: item.todo,
                        completed: false,
                        edited: false
                    };
                });
            });
        },
        addTodo() {
            if (this.newTodo.trim().length < 1) {
                return;
            }

            const dataToSave = {
                todo: this.newTodo
            };

            const url = "/api/tarefas";

            this.$http.post(url, dataToSave).then(
                response => {
                    this.todos.push({
                        id: response.body.id,
                        todo: response.body.todo,
                        completed: false,
                        edited: false
                    });
                },
                response => {
                    console.log(response);
                }
            );

            this.newTodo = "";
        },
        editTodo(todo) {
            this.beforeEditCache = todo.todo;
            todo.edited = true;
        },
        doneEdit(todo) {
            this.$http.put('/api/tarefas/' + todo.id, todo).then(response => {
                todo.todo = response.body.todo;
            }, error => {
                console.log(error);
            });

            if (this.todo === undefined || this.todo.todo.trim() === "") {
                todo.todo = this.beforeEditCache;
            }

            todo.edited = false;
        },
        cancelEdit(todo) {

            todo.edited = false;
            todo.todo = this.beforeEditCache;
        },
        removeTodo(id, index) {
            this.$http.delete("/api/tarefas/" + id).then(response => {
                this.$emit("deleted");
            });

            this.todos.splice(index, 1);
        },
        checkAllTodos() {
            this.todos.forEach(element => {
                element.completed = event.target.checked;
            });
        }
    },
    created() {
        this.initialize();
    }
};
</script>

<style>
* {
    box-sizing: border-box;
}

ul {
    margin: 0;
    padding: 0;
}

/* Style the list items */
ul li {
    cursor: pointer;
    position: relative;
    padding: 12px 8px 12px 40px;
    font-size: 18px;
    transition: 0.2s;

    /* make the list items unselectable */
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* When clicked on, add a background color and strike out text */
ul li.checked {
    background: #888;
    color: #fff;
    text-decoration: line-through;
}

/* Add a "checked" mark when clicked on */
ul li.checked::before {
    content: "";
    position: absolute;
    border-color: #fff;
    border-style: solid;
    border-width: 0 2px 2px 0;
    top: 10px;
    left: 16px;
    transform: rotate(45deg);
    height: 15px;
    width: 7px;
}

.todo-list li:last-child {
    border-bottom: none;
}



.todo-list-edit {
    border: #888;
}

.todo-list-edit:focus {
    outline: 3px solid rgb(255, 145, 0);
}

.todo-list-edit:focus-within {
    box-shadow: 0px 0.2em 2.5em #c4c4c4;
    transform: scale(1.025);
}

.completed {
    text-decoration: line-through;
    color: #888;
}

.container-extra {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 16px;
    border-top: 1px solid lightgray;
}
</style>
