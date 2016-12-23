import React from 'react';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import { addRows } from './actions';
import tableApp from './reducers';
import SearchSelectTable from './containers/SearchSelectTable';
import TableColumn from './components/TableColumn';

class SearchSelectTableWrapper extends React.Component {
  constructor(props) {
    super(props);

    this.store = createStore(tableApp, applyMiddleware(thunk));
    this.store.dispatch(addRows(props.datas));
  }

  render() {
    return (
      <Provider store={this.store}>
        <SearchSelectTable>
          {this.props.children}
        </SearchSelectTable>
      </Provider>
    );
  }
}

export {SearchSelectTableWrapper as Table, TableColumn};
