const initialState = {
  dataField: undefined,
  op: undefined,
  param: undefined
};

const filter = (state=initialState, action) => {
  switch(action.type) {
    case 'SET_FILTER':
      return Object.assign({}, state, action.filter);

    default:
      return state;
  }
};

export default filter;
