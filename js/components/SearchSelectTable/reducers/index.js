import { combineReducers } from 'redux';
import rows from './rows';
import modal from './modal';
import filter from './filter';

const tableApp = combineReducers({
  rows,
  modal,
  filter
});

export default tableApp;
