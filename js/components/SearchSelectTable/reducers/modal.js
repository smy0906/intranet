const initialState = {
  isOpen: false,
  editMode: 'add',
  data: {},
  rowIndex: undefined
};

const modal = (state=initialState, action) => {
  switch(action.type) {
    case 'OPEN_MODAL':
      return {
        isOpen:true,
        editMode: action.editMode,
        data: action.data,
        rowIndex: action.rowIndex? action.rowIndex : undefined
      };

    case 'CLOSE_MODAL':
      return Object.assign({}, state, {
        isOpen:false
      });

    default:
      return state;
  }
};

export default modal;
