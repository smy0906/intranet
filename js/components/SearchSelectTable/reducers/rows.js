import {addRow} from '../actions';

let rowIndexIncrementer = 0;

const row = (state, action) => {
  switch(action.type) {
    case 'ADD_ROW':
      return {
        isSelected: false,
        id: action.id,
        data: action.data,
      };

    case 'EDIT_ROW':
      return Object.assign({}, state, {
        data: action.data
      });

    case 'TOGGLE_ROW':
      if (state.id !== action.id) {
        return state;
      }

      return Object.assign({}, state, {
        isSelected: !state.isSelected
      });

    case 'TOGGLE_ALL_ROWS':
      return Object.assign({}, state, {
        isSelected: action.select
      });

    default:
      return state;
  }
};

const rows = (state = [], action) => {
  switch(action.type) {
    case 'INIT_ROWS':
      const initRowStates = action.datas.map(data => {
        let addRowAction = addRow(data);
        addRowAction.id = rowIndexIncrementer++;
        return row(undefined, addRowAction);
      });

      return [...initRowStates];

    case 'ADD_ROW':
      action.id = rowIndexIncrementer++;
      return [...state, row(undefined, action)];

    case 'ADD_ROWS':
      const addRowStates = action.datas.map(data => {
        let addRowAction = addRow(data);
        addRowAction.id = rowIndexIncrementer++;
        return row(undefined, addRowAction);
      });

      return [...state, ...addRowStates];

    case 'EDIT_ROW':
      return state.map(r => row(r, action));

    case 'DEL_ROW':
      let rowId = state[action.rowIndex].id;
      return state.filter(row => row.id!==rowId);

    case 'DEL_SEL_ROWS':
      return state.filter(row => !row.isSelected);

    case 'TOGGLE_ROW':
      action.id = state[action.rowIndex].id;
      return state.map(r => row(r, action));

    case 'TOGGLE_ALL_ROWS':
      return state.map(r => row(r, action));

    default:
      return state;
  }
};

export default rows;
