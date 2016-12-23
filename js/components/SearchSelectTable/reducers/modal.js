const initialState = {
  data: {},
  isOpen: false,
  editMode: 'add',
  rowIndex: undefined
};

const modal = (state=initialState, action) => {
  switch(action.type) {
    case 'OPEN_MODAL':
      return {
        data: action.data,
        isOpen:true,
        editMode: action.editMode,
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
