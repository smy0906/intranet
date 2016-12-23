import React from 'react';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import tableApp from './reducers';
import Table from './containers/SearchSelectTable';
import TableColumn from './components/TableColumn';

class SearchSelectTable extends React.Component {
  constructor(props) {
    super(props);

    let rows = props.datas.map(
      (data, i) => ({
        isSelected: false,
        id: i,
        data: data
      })
    );

    this.store = createStore(
      tableApp,
      {rows:rows},
      applyMiddleware(thunk)
    );
  }

  render() {
    return (
      <Provider store={this.store}>
        <Table>
          {this.props.children}
        </Table>
      </Provider>
    );
  }
}

export {SearchSelectTable, TableColumn};
