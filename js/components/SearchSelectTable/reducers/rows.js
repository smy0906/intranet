const initialState = {
  isSelected: false,
  id: -1,
  data:{}
};

const row = (state = initialState, action) => {
  switch(action.type) {
    case 'ADD_ROW':
      return {
        isSelected: false,
        id: action.id,
        data: action.data
      };

    case 'EDIT_ROW':
      return Object.assign({}, state, {
        data: action.data
      });

    case 'TOGGLE_ROW':
      if (state.id !== action.rowIndex) {
        return state;
      }

      return Object.assign({}, state, {
        isSelected: !state.isSelected
      });

    default:
      return state;
  }
};

const rows = (state = [], action) => {
  switch(action.type) {
    case 'ADD_ROW':
      action.id = state.length;
      return [...state, row(undefined, action)];

    case 'EDIT_ROW':
      return state.map(r => row(r, action));

    case 'DEL_ROW':
      return state.splice(action.rowIndex, 1);

    case 'TOGGLE_ROW':
      return state.map(r => row(r, action));

    default:
      return state;
  }
};

export default rows;
