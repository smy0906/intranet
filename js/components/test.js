import React from 'react';
import {BootstrapTable, TableHeaderColumn} from 'react-bootstrap-table';

let products = [{
  id: 1,
  name: "Product1",
  price: 120
}, {
  id: 2,
  name: "Product2",
  price: 80
}];

const options = {
  exportCSVText: 'my_export',
  insertText: 'my_insert',
  deleteText: 'my_delete',
  saveText: 'my_save',
  closeText: 'my_close'
};

class App extends React.Component {
  render() {
    return (
      <div>
        <BootstrapTable data={products} options={ options } striped hover insertRow>
          <TableHeaderColumn isKey dataField='id'>Product ID</TableHeaderColumn>
          <TableHeaderColumn dataField='name'>Product Name</TableHeaderColumn>
          <TableHeaderColumn dataField='price'>Product Price</TableHeaderColumn>
        </BootstrapTable>,
      </div>
    );
  }
}

export default App;
