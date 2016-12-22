import React from 'react';

import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';

import tableApp from './SearchSelectTable/reducers';
import SearchTable from './SearchSelectTable/containers/SearchSelectTable'

const store = createStore(
  tableApp,
  applyMiddleware(thunk)
);

class PaymentTable extends React.Component {
  render() {
    return (
      <Provider store={store}>
        <SearchTable schema={this.props.schema}/>
      </Provider>
    );
  }
}

export default PaymentTable;
