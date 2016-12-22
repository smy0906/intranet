const initialState = {
  data: {},
  isOpen: false,
  editMode: 'add',
  rowIndex: -1
};

const modal = (state=initialState, action) => {
  switch(action.type) {
    case 'OPEN_MODAL':
      return {
        data: action.data,
        isOpen:true,
        editMode: action.editMode,
        rowIndex: action.rowIndex? action.rowIndex : -1
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
