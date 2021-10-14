// Full spec-compliant TodoMVC with localStorage persistence
// and hash-based routing in ~120 effective lines of JavaScript.

// localStorage persistence
const BASE_URL = 'http://127.0.0.1:3101';
const JSON_HEADERS = {
  'Content-Type': 'application/json',
  'Accept': 'application/json',
};
const todoStorage = {
};

// visibility filters
const filters = {
  all(todos) {
    return todos;
  },
  active(todos) {
    return todos.filter((todo) => !todo.completed);
  },
  completed(todos) {
    return todos.filter((todo)=> todo.completed);
  }
}

// app Vue instance
const app = Vue.createApp({
  // app initial state
  data() {
    return {
      todos: [],
      newTodo: "",
      editedTodo: null,
      visibility: "all"
    }
  },

  created() {
    this.fetch();
  },

  // computed properties
  // http://vuejs.org/guide/computed.html
  computed: {
    filteredTodos() {
      return filters[this.visibility](this.todos);
    },
    remaining() {
      return filters.active(this.todos).length;
    },
    allDone: {
      get() {
        return this.remaining === 0;
      },
      set(value) {
        this.todos.forEach((todo) => {
          todo.completed = value;
        });
      }
    }
  },

  // methods that implement data logic.
  // note there's no DOM manipulation here at all.
  methods: {
    pluralize(n) {
      return n === 1 ? "item" : "items";
    },
    addTodo() {
      var value = this.newTodo && this.newTodo.trim();
      if (!value) {
        return;
      }
      // TODO: don't add todo to list until you have saved it and
      // retrieved an ID from the response
      this.todos.push({
        id: todoStorage.uid++,
        title: value,
        completed: false
      });
      this.newTodo = "";
    },

    removeTodo(todo) {
      const url = BASE_URL + '/todos/' + todo.id;
      fetch(url, {method: 'DELETE'})
        .then(response => {
          this.todos.splice(this.todos.indexOf(todo), 1);
        })
        .catch(err => console.log(err));
    },

    editTodo(todo) {
      this.beforeEditCache = todo.title;
      this.editedTodo = todo;
    },

    doneEdit(todo) {
      if (!this.editedTodo) {
        return;
      }
      this.editedTodo = null;
      todo.title = todo.title.trim();
      if (!todo.title) {
        this.removeTodo(todo);
      } else {
        this.save(todo);
      }
    },

    cancelEdit(todo) {
      this.editedTodo = null;
      todo.title = this.beforeEditCache;
    },

    removeCompleted() {
      filters.completed(this.todos)
        .forEach(todo => {
          this.removeTodo(todo);
        });
    },

    fetch() {
      fetch(BASE_URL + '/todos')
        .then(response => response.json())
        .then(data => {
          this.todos = data;
        })
        .catch(err => console.log(err));
    },

    save(todo) {
      const url = BASE_URL + '/todos/' + todo.id;
      fetch(url, {
        method: 'PATCH',
        headers: JSON_HEADERS,
        body: JSON.stringify({
          title: todo.title,
          completed: todo.completed,
        }),
      });
    }
  },

  // a custom directive to wait for the DOM to be updated
  // before focusing on the input field.
  // http://vuejs.org/guide/custom-directive.html
  directives: {
    "todo-focus": {
      updated(el, binding) {
        if (binding.value) {
          el.focus();
        }
      }
    }
  }
});

// mount
const vm = app.mount(".todoapp");

// handle routing
function onHashChange() {
  const visibility = window.location.hash.replace(/#\/?/, "");
  if (filters[visibility]) {
    vm.visibility = visibility;
  } else {
    window.location.hash = "";
    vm.visibility = "all";
  }
}

window.addEventListener("hashchange", onHashChange);
onHashChange();

