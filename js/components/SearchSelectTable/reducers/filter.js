const initialState = {
  options: [],
  selected: 0,
  op: undefined,
  param1: undefined,
  param2: undefined
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
